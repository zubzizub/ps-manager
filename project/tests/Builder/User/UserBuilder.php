<?php

declare(strict_types=1);

namespace App\Tests\Builder\User;

use App\Domain\User\Entity\User\Email;
use App\Domain\User\Entity\User\Id;
use App\Domain\User\Entity\User\User;
use DateTimeImmutable;
use http\Exception\BadMethodCallException;

class UserBuilder
{
    private Id $id;
    private DateTimeImmutable $date;

    private Email $email;
    private string $hash;
    private string $token;
    private bool $confirmed = false;

    private string $network;
    private string $identity;

    public function __construct()
    {
        $this->id = Id::next();
        $this->date = new DateTimeImmutable();
    }

    public function viaEmail(Email $email = null, string $hash = null, string $token = null): self
    {
        $clone = clone $this;
        $clone->email = $email ?? new Email('test@test.com');
        $clone->hash = $hash ?? 'hash';
        $clone->token = $token ?? 'token';
        return $clone;
    }

    public function confirmed(): self
    {
        $clone = clone $this;
        $clone->confirmed = true;
        return $clone;
    }

    public function viaNetwork(string $network = null, string $identity = null): self
    {
        $clone = clone $this;
        $clone->network = $network ?? 'google';
        $clone->identity = $identity ?? '001';
        return $clone;
    }

    public function build(): User
    {
        if (isset($this->email)) {
            $user = User::signUpByEmail(
                $this->id,
                $this->date,
                $this->email,
                $this->hash,
                $this->token
            );

            if ($this->confirmed) {
                $user->confirmSignUp();
            }
            return $user;
        }
        if (isset($this->network)) {
            return User::signUpByNetwork(
                $this->id,
                $this->date,
                $this->network,
                $this->identity
            );
        }
        throw new BadMethodCallException('Specify via method.');
    }
}
