<?php

declare(strict_types=1);

namespace App\Service\CostService\Strategy;

use App\Service\CostService\Strategy\Abstract\EarlyBookingDiscountStrategyAbstract;
use DateTime;

class JanDiscountStrategy extends EarlyBookingDiscountStrategyAbstract
{
    /**
     * Скидка начинается с 15.01.год+1
     */
    public function getDiscountStart(): DateTime
    {
        $nextYear = $this->getYearTravelStart();
        return new DateTime($nextYear . '-01-15');
    }

    /**
     * Срок окончания скидки не ограничен
     */
    public function getDiscountEnd(): ?DateTime
    {
        return null;
    }

    /**
     * Крайний срок оплаты со скидкой 31.10.год
     */
    public function getDiscountLimit(): DateTime
    {
        $year = $this->getYearTravelStart() - 1;
        return new DateTime($year . '-10-31');
    }
}
