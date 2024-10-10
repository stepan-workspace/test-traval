<?php

declare(strict_types=1);

namespace App\Service\CostService;

use App\DTO\Request\CostViewDTO;
use App\Service\CostService\DiscountCalculator;
use App\Service\CostService\Factory\AgeDiscountFactory;
use App\Service\CostService\Handler\DTO\EarlyBookingHandlerDTO;
use App\Service\CostService\Handler\EarlyBookingDiscountHandler;
use App\Service\CostService\Strategy\AprSepDiscountStrategy;
use App\Service\CostService\Strategy\JanDiscountStrategy;
use App\Service\CostService\Strategy\OctJanDiscountStrategy;
use DateTime;

class DiscountService
{
    private float $finalCost;

    public function request(CostViewDTO $requestDTO): void
    {
        // Расчёт детской скидки
        $strategy = AgeDiscountFactory::createStrategy(
            new DateTime($requestDTO->getDateOfBirth()),
            new DateTime($requestDTO->getDateTravelStart())
        );
        $calculator = new DiscountCalculator($strategy);
        $finalCost = $calculator->calculate($requestDTO->getBaseCost());

        // Расчёт скидки за реннее бронирование
        $handlerDTO = (new EarlyBookingHandlerDTO())
            ->setCost($finalCost)
            ->setDateTravelStart($requestDTO->getDateTravelStart()
                ? new DateTime($requestDTO->getDateTravelStart())
                : null
            )->setDatePayment($requestDTO->getDatePayment()
                ? new DateTime($requestDTO->getDatePayment())
                : null
            );

        $aprSepDiscount = new EarlyBookingDiscountHandler(
            new AprSepDiscountStrategy()
        );
        $octJanDiscount = new EarlyBookingDiscountHandler(
            new OctJanDiscountStrategy()
        );
        $janDiscount = new EarlyBookingDiscountHandler(
            new JanDiscountStrategy()
        );

        $aprSepDiscount
            ->setNext($octJanDiscount)
            ->setNext($janDiscount);

        $this->finalCost = $aprSepDiscount->handle($handlerDTO);
    }

    public function getFinalCost(): float
    {
        return $this->finalCost;
    }
}
