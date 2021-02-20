<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\User\Entity\User\SignUpByNetwork;

use App\Domain\Auth\Entity\User\Id;
use App\Domain\Auth\Entity\User\Network;
use App\Domain\Auth\Entity\User\User;
use App\Tests\Builder\User\UserBuilder;
use App\Tests\Unit\Utils\PrivateAccessor;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public const NETWORK = 'google';
    public const IDENTITY = '001';

    use PrivateAccessor;

    public function testSuccess(): void
    {
        $user = User::signUpByNetwork(
            $id = Id::next(),
            $date = new DateTimeImmutable(),
            self::NETWORK,
            self::IDENTITY
        );

        self::assertTrue($user->isActive());

        self::assertEquals($id, $user->getId());
        self::assertEquals($date, $user->getDate());

        $networks = $user->getNetworks();
        self::assertCount(1, $networks);
        self::assertInstanceOf(Network::class, $first = reset($networks));
        self::assertEquals(self::NETWORK, $first->getNetwork());
        self::assertEquals(self::IDENTITY, $first->getIdentity());
    }

    public function testIsExistsNetwork(): void
    {
        $user = (new UserBuilder())
            ->viaNetwork(self::NETWORK, self::IDENTITY)
            ->build();

        self::assertEquals(self::NETWORK, $user->getNetworks()[0]->getNetwork());
        $this->expectExceptionMessage('Network is already attached.');
        self::callMethod($user, 'attacheNetwork', ['google', '001']);
    }
}
