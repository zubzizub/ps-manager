<?php

declare(strict_types=1);

namespace App\Components\Ps\Parser;

use App\Components\Ps\PsGame;
use App\Components\Ps\PsGamesCollection;
use App\Domain\Store\Service\Ps\PsGameInterface;
use App\Domain\Store\Service\Ps\PsInterface;
use DateTimeImmutable;
use DomainException;
use QL\QueryList;

class Parser implements PsInterface
{
    /**
     * @param string $gameId
     * @return PsGame
     * @throws \JsonException
     */
    public function getGameById(string $gameId): PsGameInterface
    {
        $urlGame = "https://store.playstation.com/ru-ru/product/{$gameId}";
        $query = (new QueryList)->get($urlGame);
        return $this->findGameByAttr($query, $gameId);
    }

    public function getAllGames($startPage): PsGamesCollection
    {
        $urlGame = "https://store.playstation.com/ru-ru/category/4df8ce70-bb0a-41c0-a2d2-98a3781c0c78/1";
        $query = (new QueryList)->get($urlGame);
        $data = $query->find('a[class=ems-sdk-product-tile-link]')->attrs('data-telemetry-meta')->all();
        if (empty($data)) {
            throw new DomainException('Page with games not founded.');
        }

        $psCollection = [];
        foreach ($data as $key => $game) {
            $game = json_decode($game, false, 512, JSON_THROW_ON_ERROR);
            $parseDto = new PsGame();
            $parseDto->id = $game->id;
            $parseDto->title = $game->name;
            $parseDto->description = $game->name;
            $parseDto->price = $this->handlePrice($game->price) ?? 0;
            $parseDto->priceDiscount = $this->handlePrice($game->price) ?? 0;
            $parseDto->version = 'ps4';
            $parseDto->discountEndDate = new DateTimeImmutable();
            $psCollection[] = $parseDto;
        }
        return new PsGamesCollection(...$psCollection);
    }

    public function findGameByAttr($query, string $gameId): PsGameInterface
    {
        $data = $query->find('button[data-qa=mfeCtaMain#cta#action]')->attrs('data-telemetry-meta')->first();

        if (empty($data)) {
            throw new DomainException('Page with game not founded: ' . $gameId);
        }
        $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        $productDetail = $data['productDetail'][0];
        $sku = $productDetail['productId'];
        $productName = $productDetail['productName'];
        $productPriceDetail = $productDetail['productPriceDetail'][0];

        if (!isset($sku) || $sku !== $gameId) {
            throw new DomainException('Page with game not founded: ' . $gameId);
        }

        $parseDto = new PsGame();
        $parseDto->title = $productName;
        $parseDto->description = $productName;
        $parseDto->price = $this->handlePrice($productPriceDetail['originalPriceFormatted']) ?? 0;
        $parseDto->priceDiscount = $this->handlePrice($productPriceDetail['discountPriceFormatted']) ?? 0;
        $parseDto->version = 'ps4';
        $parseDto->discountEndDate = new DateTimeImmutable();

        return $parseDto;
    }

    public function findByScript(QueryList $query, string $gameId): PsGame
    {
        $data = $query->find('script[id=mfe-jsonld-tags]')->text();

        if (empty($data)) {
            throw new DomainException('Page with game not founded: ' . $gameId);
        }
        $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);

        if (!isset($data['sku']) || $data['sku'] !== $gameId) {
            throw new DomainException('Page with game not founded: ' . $gameId);
        }

        $parseDto = new PsGame();
        $parseDto->title = $data['name'] ?? '';
        $parseDto->description = $data['description'] ?? '';
        $parseDto->price = $data['offers']['price'] ?? '';
        $parseDto->priceDiscount = $data['offers']['price'] ?? '';
        $parseDto->version = 'ps4';
        $parseDto->discountEndDate = new DateTimeImmutable();

        return $parseDto;
    }

    private function handlePrice(string $price): int
    {
        return (int)trim(str_replace('.', '', str_replace('RUB', '', $price)));
    }
}
