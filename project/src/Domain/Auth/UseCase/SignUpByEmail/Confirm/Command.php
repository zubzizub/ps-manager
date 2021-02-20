<?php

declare(strict_types=1);

namespace App\Domain\Auth\UseCase\SignUpByEmail\Confirm;

class Command
{
    public string $token;
}
