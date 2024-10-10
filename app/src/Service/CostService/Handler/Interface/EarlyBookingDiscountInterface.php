<?php

declare(strict_types=1);

namespace App\Service\CostService\Handler\Interface;

use App\Service\CostService\Handler\DTO\EarlyBookingHandlerDTO;

interface EarlyBookingDiscountInterface
{
    public function setNext(EarlyBookingDiscountInterface $handler): EarlyBookingDiscountInterface;
    public function handle(EarlyBookingHandlerDTO $handlerDTO): float;
}
