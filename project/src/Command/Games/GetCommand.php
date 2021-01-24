<?php

namespace App\Command\Games;

use App\Domain\Store\Service\Ps\PsInterface;
use App\Domain\Store\UseCase\Game\Create\Handler;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Domain\Store\UseCase\Game\Create\Command as CreateCommandDto;

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

    protected function configure(): void
    {
        $this
            ->setName('games:get')
            ->setDescription('Get games from ps');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        for ($i = 0; $i <= 1; $i++) {
            $page = $i * 100;
            $gamesCollection = $this->parser->getAllGames($page);

            foreach ($gamesCollection->all() as $game) {
                $command = new CreateCommandDto();
                $command->externalId = $game->getExternalId();
                $command->title = $game->getTitle();
                $command->description = $game->getDescription();
                $command->price = $game->getPrice();
                $command->lowerPrice = $game->getLowerPrice();
                $command->imageUrl = $game->getImageUrl();
                $command->discountEndDate = $game->getDiscountEndDate();

                try {
                    $this->handler->handle($command);
                } catch (Exception $exception) {
                    echo $exception->getMessage();
                }
            }
        }
        echo 'success';
        return 0;
    }
}
