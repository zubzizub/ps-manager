<?php

declare(strict_types=1);

namespace App\Tests\Builder\User;

use App\Domain\User\Entity\Id;
use App\Domain\User\Entity\User;
use DateTimeImmutable;

class UserWithNetworkBuilder
{
    private Id $id;
    private DateTimeImmutable $date;
    private string $network;
    private string $identity;

    public function __construct()
    {
        $this->id = Id::next();
        $this->date = new DateTimeImmutable();
        $this->network = 'google';
        $this->identity = '001';
    }

    public function withNetwork(string $network): self
    {
        $this->network = $network;
        return $this;
    }

    public function withIdentity(string $identity): self
    {
        $this->identity = $identity;
        return $this;
    }

    public function withDate(DateTimeImmutable $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function build(): User
    {
        return User::signUpByNetwork(
            $this->id,
            $this->date,
            $this->network,
            $this->identity
        );
    }
}
