<?php

declare(strict_types=1);

namespace App\ReadModel\Parser\Game;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class GameFetcher
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function all(): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('parser_games')
            ->execute();

        return $stmt->fetchAll();
    }

    /**
     * @return array
     * @throws Exception
     */
    public function allIds(): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select('external_id')
            ->from('parser_games')
            ->execute();

        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
}
