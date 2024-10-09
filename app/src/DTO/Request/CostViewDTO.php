<?php

declare(strict_types=1);

namespace App\DTO\Request;

use Doctrine\DBAL\Types\Types;
use OpenApi\Attributes;
use Symfony\Component\Validator\Constraints as Assert;

class CostViewDTO
{
    #[Attributes\Property(
        description: 'Базовая стоимость в рублях',
        type: Types::INTEGER,
    )]
    #[Assert\NotBlank(
        message: "Базовая стоимость должна быть заполнена"
    )]
    #[Assert\GreaterThanOrEqual(
        value: 1,
        message: "Базовая стоимость должна быть больше нуля"
    )]
    #[Assert\Regex(
        pattern: '/^\d+$/',
        message: "Базовая стоимость должна содержать только цифры"
    )]
    private int|string|null $baseCost;

    #[Attributes\Property(
        description: 'Дата рождения участника',
        type: Types::STRING,
        example: '01.01.2020'
    )]
    #[Assert\NotBlank(
        message: "Дата рождения участника должна быть заполнена"
    )]
    #[Assert\DateTime(
        format: 'd.m.Y',
        message: "Дата рождения участника указана не корректно"
    )]
    #[Assert\Regex(
        pattern: '/^\d{2}\.\d{2}\.\d{4}$/',
        message: "Дата рождения участника должна быть в формате дд.мм.гггг"
    )]
    private string|null $dateOfBirth = null;

    #[Attributes\Property(
        description: 'Дата старта путишествия',
        type: Types::STRING,
        example: '01.01.2020'
    )]
    #[Assert\DateTime(
        format: 'd.m.Y',
        message: "Дата старта путишествия указана не корректно"
    )]
    #[Assert\Regex(
        pattern: '/^\d{2}\.\d{2}\.\d{4}$/',
        message: "Дата старта путишествия должна быть в формате дд.мм.гггг"
    )]
    private string|null $dateTravelStart = null;

    #[Attributes\Property(
        description: 'Дата оплаты',
        type: Types::STRING,
        example: '01.01.2020'
    )]
    #[Assert\DateTime(
        format: 'd.m.Y',
        message: "Дата оплаты указана не корректно"
    )]
    #[Assert\Regex(
        pattern: '/^\d{2}\.\d{2}\.\d{4}$/',
        message: "Дата оплаты должна быть в формате дд.мм.гггг"
    )]
    private string|null $datePayment = null;

    public function getBaseCost(): int
    {
        return $this->baseCost;
    }

    public function setBaseCost(int|string|null $baseCost): self
    {
        $this->baseCost = $baseCost;

        return $this;
    }

    public function getDateOfBirth(): ?string
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(?string $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getDateTravelStart(): ?string
    {
        return $this->dateTravelStart;
    }

    public function setDateTravelStart(?string $dateTravelStart): self
    {
        $this->dateTravelStart = $dateTravelStart;

        return $this;
    }

    public function getDatePayment(): ?string
    {
        return $this->datePayment;
    }

    public function setDatePayment(?string $datePayment): self
    {
        $this->datePayment = $datePayment;

        return $this;
    }
}
