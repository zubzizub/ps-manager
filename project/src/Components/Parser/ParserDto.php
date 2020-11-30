<?php

declare(strict_types=1);

namespace App\Components\Parser;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Validation;

class ParserDto
{
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
    public int $price;

    public ?int $priceDiscount;
    /**
     * @Validation\NotBlank()
     */
    public string $version;
    /**
     * @var DateTimeImmutable
     * @Validation\Date()
     */
    public DateTimeImmutable $discountEndDate;
}
