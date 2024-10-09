<?php

declare(strict_types=1);

namespace App\Controller\Cost;

use App\DTO\Builder\CostViewDTOBuilder;
use App\DTO\Request\CostViewDTO;
use App\Service\CostService\Handle\DiscountHandle;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ViewController extends AbstractController
{
    public function __construct(
        private ValidatorInterface $validator,
        private DiscountHandle $discountHandle,
    ) {}

    /**
     * Получить стоимость путешествия.
     */
    #[Route('/api/traval/cost', name: 'travel_cost', methods: ['GET'])]
    #[Attributes\RequestBody(
        required: true,
        content: new Attributes\JsonContent(
            ref: new Model(type: CostViewDTO::class)
        )
    )]
    #[Attributes\Response(
        response: 200,
        description: 'Success',
        content: new Attributes\JsonContent(
            properties: [
                new Attributes\Property(property: 'cost', type: 'integer')
            ]
        )
    )]
    #[Attributes\Response(
        response: 400,
        description: 'Во входных данных есть ошибки',
    )]
    #[Attributes\Tag(name: 'Cost')]
    public function view(Request $request): JsonResponse
    {
        $requestDTO = CostViewDTOBuilder::build(
            $request->query->all()
        );

        $errors = [];
        foreach ($this->validator->validate($requestDTO) as $error) {
            $errors[] = $error->getMessage();
        }

        if (!empty($errors)) {
            return new JsonResponse([
                'data' => null,
                'errors' => $errors,
            ], Response::HTTP_BAD_REQUEST);
        }

        $this->discountHandle->request($requestDTO);

        return new JsonResponse([
            'status' => 'success',
            'cost' => $this->discountHandle->getFinalCost(),
        ], Response::HTTP_OK);
    }
}
