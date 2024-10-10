<?php

declare(strict_types=1);

namespace App\Controller\Cost;

use App\DTO\Builder\CostViewDTOBuilder;
use App\DTO\Request\CostViewDTO;
use App\Service\CostService\DiscountService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ViewController extends AbstractController
{
    public function __construct(
        private ValidatorInterface $validator,
        private DiscountService $discountService,
    ) {}

    /**
     * Получить стоимость путешествия.
     */
    #[Route('/api/traval/cost', name: 'travel_cost', methods: ['GET'])]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: new Model(type: CostViewDTO::class)
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'cost', type: 'integer')
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Во входных данных есть ошибки',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'data', type: 'object', nullable: true, example: null),
                new OA\Property(
                    property: 'errors',
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'field', type: 'string'),
                            new OA\Property(property: 'message', type: 'string')
                        ]
                    )
                ),
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Маршрут не найден или не существует',    )]
    #[OA\Tag(name: 'Cost')]
    public function view(Request $request): JsonResponse
    {
        $requestDTO = CostViewDTOBuilder::build(
            $request->query->all()
        );

        $errors = [];
        foreach ($this->validator->validate($requestDTO) as $error) {
            $errors[] = [
                'field' => $error->getPropertyPath(),
                'message' => $error->getMessage()
            ];
        }

        if (!empty($errors)) {
            return new JsonResponse([
                'data' => null,
                'errors' => $errors,
            ], Response::HTTP_BAD_REQUEST);
        }

        $this->discountService->request($requestDTO);

        return new JsonResponse([
            'status' => 'success',
            'cost' => $this->discountService->getFinalCost(),
        ], Response::HTTP_OK);
    }
}
