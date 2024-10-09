<?php

declare(strict_types=1);

namespace App\Service\CostService\Handle;

use App\DTO\Request\CostViewDTO;
use App\Service\CostService\DiscountCalculator;
use App\Service\CostService\Factory\AgeDiscountFactory;
use DateTime;

class DiscountHandle
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



        $this->setFinalCost($finalCost);
    }

    public function setFinalCost(float $finalCost): self
    {
        $this->finalCost = $finalCost;

        return $this;
    }

    public function getFinalCost(): float
    {
        return $this->finalCost;
    }
}