<?php

declare(strict_types=1);

namespace App\Service\CostService\Strategy\Abstract;

use App\Service\CostService\Handler\DTO\EarlyBookingHandlerDTO;
use DateTime;

abstract class EarlyBookingDiscountStrategyAbstract
{
    private ?EarlyBookingHandlerDTO $handlerDTO = null;

    abstract public function getDiscountStart(): DateTime;

    abstract public function getDiscountEnd(): ?DateTime;

    abstract public function getDiscountLimit(): DateTime;

    public function getDiscount(DateTime $datePayment): float
    {
        $limitDate = new DateTime($this->getDiscountLimit()->format('Y-m-d'));
        return match (true) {
            $this->getModifyMonthByDateTime($limitDate, '-2', 'ymd') >= $datePayment->format('ymd') => 0.07,
            $this->getModifyMonthByDateTime($limitDate, '-1', 'ymd') >= $datePayment->format('ymd') => 0.05,
            $limitDate->format('ymd') >= $datePayment->format('ymd') => 0.03,
            default => 0
        };
    }

    /**
     * Проверяет диапазон периода скидки по дате начала путешествия
     */
    public function checkPeriodTravel(?DateTime $dateTravelStart): bool
    {
        return $dateTravelStart !== null
            && $dateTravelStart >= $this->getDiscountStart()
            && ($this->getDiscountEnd() === null 
                || $dateTravelStart <= $this->getDiscountEnd());
    }

    /**
     * Проверяет крайнюю дату получения скидки по дате оплаты
     */
    public function checkPeriodPayment(?DateTime $datePayment): bool
    {
        return $datePayment !== null
            && $datePayment <= $this->getDiscountLimit();
    }

    public function checkPeriods(?DateTime $dateTravelStart, ?DateTime $datePayment): bool
    {
        return $this->checkPeriodTravel($dateTravelStart) && $this->checkPeriodPayment($datePayment);
    }

    public function setHandlerDTO(EarlyBookingHandlerDTO $handlerDTO): self
    {
        $this->handlerDTO = $handlerDTO;

        return $this;
    }

    public function getHandlerDTO(): ?EarlyBookingHandlerDTO
    {
        return $this->handlerDTO;
    }

    public function getYearTravelStart(): ?int
    {
        return (int)$this->getHandlerDTO()?->getDateTravelStart()?->format('Y');
    }

    protected function getModifyMonthByDateTime(
        DateTime $dateTime, string $modifier, string $format = null
    ): DateTime|string
    {
        $year = $dateTime->format('Y');
        $month = $dateTime->format('m');
        $day = $dateTime->format('d');

        $isLastDayOfMonth = $day == (new DateTime("{$year}-{$month}"))
            ->modify('last day of this month')
            ->format('d');

        $dateModified = (new DateTime("{$year}-{$month}"))
            ->modify($modifier . ' month');
        
        $dayModified = $isLastDayOfMonth
            ? $dateModified->modify('last day of this month')->format('d')
            : $day;

        $dateResult = new DateTime($dateModified->format('Y-m-') . $dayModified);

        return $format !== null
            ? $dateResult->format($format)
            : $dateResult;
    }
}
