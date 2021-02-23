<?php

namespace App\Infrastructure\User\Service;

use App\Domain\Auth\Entity\User\Email;
use App\Domain\Auth\Service\ConfirmTokenSenderInterface;

class ConfirmTokenServiceInterface implements ConfirmTokenSenderInterface
{
    public function send(Email $email, string $token): void
    {

    }
}
