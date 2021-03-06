<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\User\Entity\User\Reset;

use App\Domain\Auth\Entity\User\ResetToken;
use App\Tests\Builder\User\UserWithEmailBuilder;
use App\Tests\Builder\User\UserWithNetworkBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testSuccess(): void
    {
        $now = new DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+ 1 day'));

        $user = (new UserWithEmailBuilder())->confirmed()->build();

        $user->requestPasswordReset($token, $now);

        self::assertNotNull($user->getResetToken());
    }

    public function testAlready(): void
    {
        $now = new DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));

        $user = (new UserWithEmailBuilder())->confirmed()->build();

        $user->requestPasswordReset($token, $now);
        $this->expectExceptionMessage('Resetting is already requested.');
        $user->requestPasswordReset($token, $now);
    }

    public function testExpired(): void
    {
        $now = new DateTimeImmutable();

        $user = (new UserWithEmailBuilder())->confirmed()->build();

        $token1 = new ResetToken('token', $now->modify('+1 day'));
        $user->requestPasswordReset($token1, $now);

        self::assertEquals($token1, $user->getResetToken());

        $token2 = new ResetToken('token', $now->modify('+3 day'));
        $user->requestPasswordReset($token2, $now->modify('+2 day'));

        self::assertEquals($token2, $user->getResetToken());
    }

    public function testWithoutEmail(): void
    {
        $now = new DateTimeImmutable();
        $user = (new UserWithNetworkBuilder())->build();

        $token = new ResetToken('token', $now->modify('+1 day'));

        $this->expectExceptionMessage('Email is not specified.');
        $user->requestPasswordReset($token, new DateTimeImmutable());
    }

    public function testNotConfirmed(): void
    {
        $now = new DateTimeImmutable();
        $user = (new UserWithEmailBuilder())->build();

        $token = new ResetToken('token', $now->modify('+1 day'));

        $this->expectExceptionMessage('Auth is not active.');
        $user->requestPasswordReset($token, $now);
    }
}
