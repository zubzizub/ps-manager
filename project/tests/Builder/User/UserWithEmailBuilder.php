<?php

declare(strict_types=1);

namespace App\Tests\Builder\User;

use App\Domain\User\Entity\Email;
use App\Domain\User\Entity\Id;
use App\Domain\User\Entity\User;
use DateTimeImmutable;

class UserWithEmailBuilder
{
    private Id $id;
    private DateTimeImmutable $date;
    private Email $email;
    private string $hash;
    private string $token;
    private bool $isConfirmed = false;

    public function __construct()
    {
        $this->id = Id::next();
        $this->date = new DateTimeImmutable();
        $this->email = new Email('test@test.com');
        $this->hash = 'hash';
        $this->token = 'token';
    }

    public function withEmail(string $email): self
    {
        $this->email = new Email($email);
        return $this;
    }

    public function confirmed(): self
    {
        $this->isConfirmed = true;
        return $this;
    }

    public function withDate(DateTimeImmutable $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function build(): User
    {
        $user = User::signUpByEmail(
            $this->id,
            $this->date,
            $this->email,
            $this->hash,
            $this->token
        );

        if ($this->isConfirmed) {
            $user->confirmSignUp();
        }

        return $user;
    }
}
