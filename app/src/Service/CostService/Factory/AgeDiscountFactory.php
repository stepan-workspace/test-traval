<?php

declare(strict_types=1);

namespace App\Service\CostService\Factory;

use App\Service\CostService\Strategy\ChildDiscountStrategy;
use App\Service\CostService\Strategy\InfantDiscountStrategy;
use App\Service\CostService\Strategy\Interface\DiscountStrategyInterface;
use App\Service\CostService\Strategy\MinorDiscountStrategy;
use App\Service\CostService\Strategy\NoDiscountStrategy;
use App\Service\CostService\Strategy\TeenDiscountStrategy;
use DateTime;

class AgeDiscountFactory
{
    public static function createStrategy(DateTime $dateOfBirth, DateTime $dateTravelStart): DiscountStrategyInterface
    {
        $age = self::calculateAge($dateOfBirth, $dateTravelStart);

        return match (true) {
            $age < 3 => new InfantDiscountStrategy(),
            $age < 6 => new ChildDiscountStrategy(),
            $age < 12 => new TeenDiscountStrategy(),
            $age < 18 => new MinorDiscountStrategy(),
            default => new NoDiscountStrategy()
        };
    }

    private static function calculateAge(DateTime $dateOfBirth, DateTime $dateTravelStart): int
    {
        $interval = $dateOfBirth->diff($dateTravelStart);
        return $interval->y;
    }
}