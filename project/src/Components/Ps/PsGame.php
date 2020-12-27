<?php

declare(strict_types=1);

namespace App\Components\Ps;

use App\Domain\Store\Service\Ps\PsGameInterface;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Validation;

class PsGame implements PsGameInterface
{
    /**
     * @Validation\NotBlank()
     */
    public string $id;

    /**
     * @Validation\NotBlank()
     */
    public string $title;
    /**
     * @Validation\NotBlank()
     */
    public string $description;
    /**
     * @Validation\NotBlank()
     */
    public $price;

    public ?int $priceDiscount;
    /**
     * @Validation\NotBlank()
     */
    public string $version;

    public string $urlImage;
    /**
     * @var DateTimeImmutable
     * @Validation\Date()
     */
    public DateTimeImmutable $discountEndDate;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getPriceDiscount(): int
    {
        return $this->priceDiscount;
    }

    public function getDiscountEndDate(): DateTimeImmutable
    {
        return $this->discountEndDate;
    }

    public function getVersion(): string
    {
        return $this->version;
    }
}
