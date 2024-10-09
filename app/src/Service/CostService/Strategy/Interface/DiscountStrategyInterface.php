<?php
declare(strict_types=1);

namespace App\Service\CostService\Strategy\Interface;

interface DiscountStrategyInterface
{
    public function calculateDiscount(float $baseCost): float;
}
