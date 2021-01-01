<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\User\Entity\User\SignUp;

use App\Domain\User\Entity\Email;
use App\Domain\User\Entity\Id;
use App\Domain\User\Entity\User;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ConfirmTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = $this->buildSignedUpUser();

        $user->confirmSignUp();

        self::assertTrue($user->isActive());
        self::assertFalse($user->isWait());
        self::assertNull($user->getConfirmToken());
    }

    public function testAlreadyConfirmed(): void
    {
        $user = $this->buildSignedUpUser();

        $user->confirmSignUp();
        $this->expectExceptionMessage('User is already confirmed.');
        $user->confirmSignUp();
    }

    private function buildSignedUpUser(): User
    {
        return new User(
            Id::next(),
            new Email('test@test.com'),
            'hash',
            'token',
            new DateTimeImmutable()
        );
    }
}
