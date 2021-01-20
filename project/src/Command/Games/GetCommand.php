<?php

/**
 *
 */

namespace App\Command\Games;

use App\Components\Ps\PsGame;
use App\Domain\Store\Service\Ps\PsInterface;
use App\Domain\Store\UseCase\Game\Create\Handler;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetCommand extends Command
{
    private PsInterface $parser;
    private Handler $handler;

    public function __construct(PsInterface $parser, Handler $handler)
    {
        $this->parser = $parser;
        $this->handler = $handler;
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $games = $this->parser->getAllGames();

        /** @var PsGame $game */
        foreach ($games as $game) {
            $command = new \App\Domain\Store\UseCase\Game\Create\Command();
            $command->externalId = $game->id;

            try {
                $this->handler->handle($command);
            } catch (Exception $exception) {
                return 1;
            }
        }
        return 0;
    }
}
