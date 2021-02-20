<?php

declare(strict_types=1);

namespace App\Domain\Auth\Service;

use App\Domain\Auth\Entity\User\Email;
use App\Domain\Auth\Entity\User\ResetToken;

interface ResetTokenSenderInterface
{
    public function send(Email $email, ResetToken $resetToken): void;
}
