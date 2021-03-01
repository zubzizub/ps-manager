<?php

declare(strict_types=1);

namespace App\Domain\Store\Entity\Game;

use DateTimeImmutable;
use Doctrine\ORM\Mapping;

/**
 * @Mapping\Embeddable
 */
class Price
{
    /**
     * @Mapping\Column (type="integer")
     */
    private int $price;

    /**
     * @Mapping\Column (type="integer")
     */
    private int $lowerPrice;

    /**
     * @Mapping\Column (type="date_immutable", nullable=true)
     */
    private ?DateTimeImmutable $discountEndDate = null;

    public function __construct(
        int $price,
        int $lowerPrice,
        DateTimeImmutable $discountEndDate
    ) {
        $this->price = $price;
        $this->lowerPrice = $lowerPrice;
        $this->discountEndDate = $discountEndDate;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getDiscountEndDate(): ?DateTimeImmutable
    {
        return $this->discountEndDate;
    }
}
