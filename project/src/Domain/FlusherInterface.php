<?php

declare(strict_types=1);

namespace App\Domain;

interface FlusherInterface
{
    public function flush(): void;
}
