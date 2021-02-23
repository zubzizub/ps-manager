<?php

declare(strict_types=1);

namespace App\Domain\Auth\Service;

use App\Domain\Auth\Entity\User\Email;

interface ConfirmTokenSenderInterface
{
    public function send(Email $email, string $token): void;
}
