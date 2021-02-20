<?php

declare(strict_types=1);

namespace App\Domain\Auth\UseCase\Reset\Reset;

class Command
{
    public string $token;
    public string $password;
}
