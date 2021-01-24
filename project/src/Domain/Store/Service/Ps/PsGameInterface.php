<?php

declare(strict_types=1);

namespace App\Domain\Store\Service\Ps;

interface PsGameInterface
{
    public function getExternalId();

    public function getTitle();

    public function getDescription();

    public function getPrice();

    public function getLowerPrice();

    public function getDiscountEndDate();

    public function getVersion();

    public function getImageUrl();
}
