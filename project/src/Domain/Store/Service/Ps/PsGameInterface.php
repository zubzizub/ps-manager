<?php

declare(strict_types=1);

namespace App\Domain\Store\Service\Ps;

use DateTimeImmutable;

interface PsGameInterface
{
    public function getTitle(): string;

    public function getDescription(): string;

    public function getPrice(): int;

    public function getPriceDiscount(): int;

    public function getDiscountEndDate(): DateTimeImmutable;

    public function getVersion(): string;

    public function getUrlImage(): string;
}
