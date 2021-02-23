<?php

declare(strict_types=1);

namespace App\Infrastructure\User;

use App\Domain\Auth\FlusherInterface;
use Doctrine\ORM\EntityManagerInterface;

class Flusher implements FlusherInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function flush(): void
    {
        $this->em->flush();
    }
}
