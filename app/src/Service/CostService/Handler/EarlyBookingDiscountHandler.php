<?php

declare(strict_types=1);

namespace App\Service\CostService\Handler;

use App\Service\CostService\Handler\Abstract\EarlyBookingDiscountAbstract;
use App\Service\CostService\Handler\DTO\EarlyBookingHandlerDTO;
use App\Service\CostService\Strategy\Abstract\EarlyBookingDiscountStrategyAbstract;

class EarlyBookingDiscountHandler extends EarlyBookingDiscountAbstract
{
    public function __construct(
        private EarlyBookingDiscountStrategyAbstract $strategy
    ) { }

    public function handle(EarlyBookingHandlerDTO $handlerDTO): float
    {
        $this->strategy->setHandlerDTO($handlerDTO);

        $check = $this->strategy->checkPeriods(
            $handlerDTO->getDateTravelStart(),
            $handlerDTO->getDatePayment()
        );

        if ($check) {
            $discount = $this->strategy->getDiscount($handlerDTO->getDatePayment());
            if ($discount > 0) {
                // 3%, 5%, 7% скидки, но не более 1500
                return $handlerDTO->getCost() - min($discount * $handlerDTO->getCost(), 1500);
            }
        }

        return parent::handle($handlerDTO);
    }
}
