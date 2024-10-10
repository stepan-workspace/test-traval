<?php

declare(strict_types=1);

namespace App\Service\CostService\Strategy;

use App\Service\CostService\Strategy\Interface\DiscountStrategyInterface;

class TeenDiscountStrategy implements DiscountStrategyInterface
{
    public function calculateDiscount(float $baseCost): float
    {
        $discount = $baseCost * 0.3;
        return min($discount, 4500); // 30% скидки, но не более 4500
    }
}
