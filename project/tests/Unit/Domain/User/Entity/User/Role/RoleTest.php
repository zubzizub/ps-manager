<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\User\Entity\User\Role;

use App\Domain\User\Entity\User\Role;
use App\Tests\Builder\User\UserWithEmailBuilder;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserWithEmailBuilder())->build();

        self::assertTrue($user->getRole()->isUser());

        $user->changeRole(Role::admin());

        self::assertTrue($user->getRole()->isAdmin());
        self::assertFalse($user->getRole()->isUser());
    }

    public function testAlready(): void
    {
        $user = (new UserWithEmailBuilder())->build();

        $this->expectExceptionMessage('User already has this role.');
        $user->changeRole(Role::user());
    }
}
