<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase\SignUpByEmail\Confirm;

class Command
{
    public string $token;
}
