<?php

declare(strict_types=1);

namespace App\DTO\Builder;

use App\DTO\Request\CostViewDTO;

class CostViewDTOBuilder
{
    public static function build(array $requestData): CostViewDTO
    {
        return (new CostViewDTO())
            ->setBaseCost($requestData['base_cost'] ?? null)
            ->setDateOfBirth($requestData['date_of_birth'] ?? null)
            ->setDateTravelStart($requestData['date_travel_start'] ?? null)
            ->setDatePayment($requestData['date_payment'] ?? null);
    }
}
