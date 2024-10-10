<?php

declare(strict_types=1);

namespace App\Service\CostService\Strategy;

use App\Service\CostService\Strategy\Abstract\EarlyBookingDiscountStrategyAbstract;
use DateTime;

class AprSepDiscountStrategy extends EarlyBookingDiscountStrategyAbstract
{
    /**
     * Скидка начинается с 01.04.год+1
     */
    public function getDiscountStart(): DateTime
    {
        $nextYear = $this->getYearTravelStart();
        return new DateTime($nextYear . '-04-01');
    }

    /**
     * Скидка заканчивается 30.09.год+1
     */
    public function getDiscountEnd(): DateTime
    {
        $nextYear = $this->getYearTravelStart();
        return new DateTime($nextYear . '-09-30');
    }

    /**
     * Крайний срок оплаты со скидкой 31.01.год+1
     */
    public function getDiscountLimit(): DateTime
    {
        $nextYear = $this->getYearTravelStart();
        return new DateTime($nextYear . '-01-31');
    }
}
