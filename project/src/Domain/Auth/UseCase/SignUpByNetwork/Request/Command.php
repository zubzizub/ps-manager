<?php

declare(strict_types=1);

namespace App\Domain\Auth\UseCase\SignUpByNetwork\Request;

class Command
{
    public string $network;
    public string $identity;
}
