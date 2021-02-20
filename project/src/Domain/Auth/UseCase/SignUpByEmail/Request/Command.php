<?php

declare(strict_types=1);

namespace App\Domain\Auth\UseCase\SignUpByEmail\Request;

class Command
{
    public string $email;
    public string $password;
}
