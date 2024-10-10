<?php

declare(strict_types=1);

namespace App\Service\CostService\Strategy;

use App\Service\CostService\Strategy\Interface\DiscountStrategyInterface;

class NoDiscountStrategy implements DiscountStrategyInterface
{
    public function calculateDiscount(float $baseCost): float
    {
        return $baseCost; // Без скидки
    }
}
