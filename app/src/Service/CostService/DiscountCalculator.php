<?php
declare(strict_types=1);

namespace App\Service\CostService;

use App\Service\CostService\Strategy\Interface\DiscountStrategyInterface;

class DiscountCalculator
{
    public function __construct(
        private DiscountStrategyInterface $strategy
    ) { }

    public function calculate(float $baseCost): float
    {
        return $this->strategy->calculateDiscount($baseCost);
    }
}
