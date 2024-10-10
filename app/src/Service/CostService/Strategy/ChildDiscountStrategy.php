<?php

declare(strict_types=1);

namespace App\Service\CostService\Strategy;

use App\Service\CostService\Strategy\Interface\DiscountStrategyInterface;

class ChildDiscountStrategy implements DiscountStrategyInterface
{
    public function calculateDiscount(float $baseCost): float
    {
        return $baseCost * 0.2; // 80% скидки
    }
}
