<?php

declare(strict_types=1);

namespace App\Model\Parser\UseCase\Game\Create;

use Symfony\Component\Validator\Constraints as Validation;

class Command
{
    /**
     * @Validation\NotBlank()
     */
    public string $externalId;
}
