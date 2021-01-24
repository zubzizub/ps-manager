<?php

declare(strict_types=1);

namespace App\Components\Ps;

use App\Domain\Store\Service\Ps\PsGameInterface;
use DateTimeImmutable;

class PsGame implements PsGameInterface
{
    public $externalId;

    public $title;

    public $description;

    public $price;

    public $lowerPrice;

    public $version;

    public $imageUrl;

    public ?DateTimeImmutable $discountEndDate;

    public function getExternalId(): string
    {
        return $this->externalId;
    }

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

    public function getLowerPrice(): int
    {
        return $this->lowerPrice;
    }

    public function getDiscountEndDate(): ?DateTimeImmutable
    {
        return $this->discountEndDate;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }
}
