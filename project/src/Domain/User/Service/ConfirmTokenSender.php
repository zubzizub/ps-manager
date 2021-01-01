<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Entity\Email;

interface ConfirmTokenSender
{
    public function send(Email $email, string $token): void;
}
