<?php

declare(strict_types=1);

namespace App\Domain\Auth;

interface FlusherInterface
{
    public function flush(): void;
}
