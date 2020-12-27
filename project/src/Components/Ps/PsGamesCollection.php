<?php

declare(strict_types=1);

namespace App\Components\Ps;

use App\Domain\Store\Service\Ps\PsGameInterface;
use App\Domain\Store\Service\Ps\PsGamesCollectionInterface;

class PsGamesCollection implements PsGamesCollectionInterface
{
    private array $collection;

    public function __construct(PsGameInterface ...$psGame)
    {
        $this->collection = $psGame;
    }

    public function add(PsGameInterface $psGame): void
    {
        $this->collection[] = $psGame;
    }

    public function all(): array
    {
        return $this->collection;
    }
}
