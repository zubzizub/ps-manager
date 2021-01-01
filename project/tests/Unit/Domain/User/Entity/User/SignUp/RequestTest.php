<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\User\Entity\User\SignUp;

use App\Domain\User\Entity\Email;
use App\Domain\User\Entity\Id;
use App\Domain\User\Entity\User;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = new User(
            $id = Id::next(),
            $email = new Email('test@test.com'),
            $password = 'hash',
            $token = 'token',
            $date = new DateTimeImmutable()
        );

        self::assertEquals($id, $user->getId());
        self::assertEquals($email, $user->getEmail());
        self::assertEquals($password, $user->getPasswordHash());
        self::assertEquals($token, $user->getConfirmToken());
        self::assertEquals($date, $user->getDate());

        self::assertTrue($user->isWait());
        self::assertFalse($user->isActive());
    }
}
