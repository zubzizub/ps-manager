<?php

declare(strict_types=1);

namespace App\Domain\Store\UseCase\Game\Create;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Validation;

class Command
{
    /**
     * @Validation\NotBlank()
     */
    public string $externalId;

    public string $title = '';

    public string $description = '';

    public int $price = 0;

    public int $lowerPrice = 0;

    public ?string $imageUrl;

    public string $version;

    public ?DateTimeImmutable $discountEndDate;
}
