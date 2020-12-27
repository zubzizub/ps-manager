<?php

declare(strict_types=1);

namespace App\Domain\Store\Service\Ps;

interface PsGamesCollectionInterface
{
    public function add(PsGameInterface $psGame): void;

    /**
     * @return PsGameInterface[]
     */
    public function all(): array;
}
