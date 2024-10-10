<?php

declare(strict_types=1);

namespace App\Service\CostService\Handler\DTO;

use DateTime;

class EarlyBookingHandlerDTO
{
    private float $cost;

    private ?DateTime $dateTravelStart = null;

    private ?DateTime $datePayment = null;

    public function getCost(): float
    {
        return $this->cost;
    }

    public function setCost(float $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getDateTravelStart(): ?DateTime
    {
        return $this->dateTravelStart;
    }

    public function setDateTravelStart(?DateTime $dateTravelStart): self
    {
        $this->dateTravelStart = $dateTravelStart;

        return $this;
    }

    public function getDatePayment(): ?DateTime
    {
        return $this->datePayment;
    }

    public function setDatePayment(?DateTime $datePayment): self
    {
        $this->datePayment = $datePayment;

        return $this;
    }
}
