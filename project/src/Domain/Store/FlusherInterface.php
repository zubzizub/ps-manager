<?php

declare(strict_types=1);

namespace App\Domain\Store;

interface FlusherInterface
{
    public function flush(): void;
}
