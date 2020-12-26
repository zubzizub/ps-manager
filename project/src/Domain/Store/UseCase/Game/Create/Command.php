<?php

declare(strict_types=1);

namespace App\Domain\Store\UseCase\Game\Create;

use Symfony\Component\Validator\Constraints as Validation;

class Command
{
    /**
     * @Validation\NotBlank()
     */
    public string $externalId;
}
