<?php

declare(strict_types=1);

namespace App\Service\CostService\Strategy;

use App\Service\CostService\Strategy\Interface\DiscountStrategyInterface;

class MinorDiscountStrategy implements DiscountStrategyInterface
{
    public function calculateDiscount(float $baseCost): float
    {
        return $baseCost * 0.9; // 10% скидки
    }
}