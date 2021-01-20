<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Entity\User\Email;
use App\Domain\User\Entity\User\ResetToken;

interface ResetTokenSenderInterface
{
    public function send(Email $email, ResetToken $resetToken): void;
}