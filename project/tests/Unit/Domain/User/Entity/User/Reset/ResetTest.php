<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\User\Entity\User\Reset;

use App\Domain\User\Entity\User\ResetToken;
use App\Tests\Builder\User\UserWithEmailBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ResetTest extends TestCase
{
    public const HASH_RESET = 'hash-reset';

    public function testSuccess(): void
    {
        $now = new DateTimeImmutable();
        $user = (new UserWithEmailBuilder())->confirmed()->build();
        $resetToken = new ResetToken('token', $now->modify('+1 day'));

        $user->requestPasswordReset($resetToken, $now);

        self::assertNotNull($user->getResetToken());

        $user->passwordReset(self::HASH_RESET, $now);

        self::assertNull($user->getResetToken());
        self::assertEquals(self::HASH_RESET, $user->getPasswordHash());
    }

    public function testExpiredToken(): void
    {
        $now = new DateTimeImmutable();
        $user = (new UserWithEmailBuilder())->confirmed()->build();
        $resetToken = new ResetToken('token', $now);

        $user->requestPasswordReset($resetToken, $now);

        $this->expectExceptionMessage('Reset token is expired.');
        $user->passwordReset(self::HASH_RESET, $now);
    }

    public function testNotRequested(): void
    {
        $now = new DateTimeImmutable();
        $user = (new UserWithEmailBuilder())->confirmed()->build();

        $this->expectExceptionMessage('Resetting is not requested.');
        $user->passwordReset(self::HASH_RESET, $now);
    }
}
