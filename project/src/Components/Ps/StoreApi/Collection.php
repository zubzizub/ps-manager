<?php

declare(strict_types=1);

namespace App\Components\Ps\StoreApi;

use App\Components\Ps\PsGame;
use App\Components\Ps\PsGamesCollection;
use App\Domain\Store\Service\Ps\PsGameInterface;
use App\Domain\Store\Service\Ps\PsGamesCollectionInterface;
use App\Domain\Store\Service\Ps\PsInterface;
use DateTimeImmutable;
use DomainException;
use Exception;
use Games;

class Collection implements PsInterface
{
    /**
     * @param string $gameId
     * @return PsGameInterface
     * @throws Exception
     */
    public function getGameById(string $gameId): PsGameInterface
    {
        $endpoint = sprintf(
            'https://store.playstation.com/store/api/chihiro/00_09_000/container/ru/ru/999/%s',
            $gameId
        );

        try {
            $dataRequest = file_get_contents($endpoint);
        } catch (Exception $e) {
            throw new DomainException('error game.');
        }

        $game = json_decode($dataRequest, false, 512, JSON_THROW_ON_ERROR);

        $psGame = new PsGame();

        $psGame->id = $game->id;
        $psGame->title = $game->name;
        $psGame->description = $game->long_desc;
        $psGame->price = $game->default_sku->price/100 ?? 0;

        if (isset($game->default_sku->rewards[0])) {
            $reward = $game->default_sku->rewards[0];
            $psGame->priceDiscount = $this->getPriceDiscount($psGame->price, $reward->discount);
            $psGame->discountEndDate = new DateTimeImmutable($reward->campaigns[0]->end_date) ;
        } else {
            $psGame->priceDiscount = 0;
            $psGame->discountEndDate = new DateTimeImmutable() ;
        }

        $psGame->urlImage = $game->images[0]->url;

        return $psGame;
    }

    public function getAllGames(): PsGamesCollectionInterface
    {
        $freeToPlay = (new Games('ru', 'ru'))->onlyOnPlaystation(0, 100);

        $psGameCollection = new PsGamesCollection();

        foreach ($freeToPlay as $game) {
            $psGame = new PsGame();
            $psGame->id = $game->id;
            $psGame->title = $game->name;
            $psGame->price = $game->default_sku->price/100 ?? 0;
            $psGame->urlImage = $game->images[0]->url;
            $psGameCollection->add($psGame);
        }

        return $psGameCollection;
    }

    private function getPriceDiscount(int $price, int $percent): int
    {
        return $price - (int)(($price * $percent) / 100) ?? 0;
    }
}
