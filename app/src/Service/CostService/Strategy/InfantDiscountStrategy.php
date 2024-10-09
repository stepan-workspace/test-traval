<?php

declare(strict_types=1);

namespace App\Service\CostService\Strategy;

use App\Service\CostService\Strategy\Interface\DiscountStrategyInterface;

class InfantDiscountStrategy implements DiscountStrategyInterface
{
    public function calculateDiscount(float $baseCost): float
    {
        return 0; // от 0 до 3
    }
}