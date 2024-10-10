<?php

declare(strict_types=1);

namespace App\Service\CostService\Strategy;

use App\Service\CostService\Strategy\Abstract\EarlyBookingDiscountStrategyAbstract;
use DateTime;

class OctJanDiscountStrategy extends EarlyBookingDiscountStrategyAbstract
{
    /**
     * Скидка начинается с 01.10.год
     */
    public function getDiscountStart(): DateTime
    {
        $year = $this->getYearTravelStart() - 1;
        return new DateTime($year . '-10-01');
    }

    /**
     * Скидка заканчивается 14.01.год+1
     */
    public function getDiscountEnd(): DateTime
    {
        $nextYear = $this->getYearTravelStart();
        return new DateTime($nextYear . '-01-14');
    }

    /**
     * Крайний срок оплаты со скидкой 31.05.год
     */
    public function getDiscountLimit(): DateTime
    {
        $year = $this->getYearTravelStart() - 1;
        return new DateTime($year . '-05-31');
    }
}
