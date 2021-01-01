<?php

declare(strict_types=1);

namespace App\Domain\User;

interface FlusherInterface
{
    public function flush(): void;
}
