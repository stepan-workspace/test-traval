<?php

declare(strict_types=1);

namespace App\Service\CostService\Handler\Abstract;

use App\Service\CostService\Handler\DTO\EarlyBookingHandlerDTO;
use App\Service\CostService\Handler\Interface\EarlyBookingDiscountInterface;

abstract class EarlyBookingDiscountAbstract implements EarlyBookingDiscountInterface
{
    private ?EarlyBookingDiscountInterface $nextHandler = null;

    public function setNext(EarlyBookingDiscountInterface $handler): EarlyBookingDiscountInterface
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function handle(EarlyBookingHandlerDTO $handlerDTO): float
    {
        if ($this->nextHandler) {
            return $this->nextHandler->handle($handlerDTO);
        }

        return $handlerDTO->getCost();
    }
}
