<?php

namespace Kachuru\Vindinium\Display;

use Kachuru\Util\ConsoleOutput;
use Kachuru\Util\ConsoleWindow;
use Kachuru\Vindinium\Bot\Bot;
use Kachuru\Vindinium\Game\Game;

class StandardDisplay implements Display
{
    /**
     * @var ConsoleOutput
     */
    private $consoleOutput;

    public function getHandle(): string
    {
        return 'standard';
    }

    public function prepare(int $boardSize)
    {
        $this->consoleOutput = (new ConsoleOutput(
            [
                new ConsoleWindow(1, 1, 118, 1),
                new ConsoleWindow(1, 3, $boardSize * 2, $boardSize),
                new ConsoleWindow(($boardSize * 2) + 2, 3, 117 - ($boardSize * 2), $boardSize)
            ]
        ))->prepare();
    }

    public function write(Game $game, Bot $bot)
    {
        $state = $game->getState();
        $this->consoleOutput->write(
            0,
            sprintf(
                ' Game: %s - Turn: %d    Bot: %s',
                $state->getViewUrl(),
                $game->getState()->getTurn(),
                $bot->getName()
            )
        );

        $this->consoleOutput->write(1, $game->getBoard());

        $lines = [];
        foreach ($game->getHeroes() as $hero) {
            $lines[] = " " . $hero;
            if ($hero->getId() == $game->getHero()->getId()) {
                $lines[]
                    = sprintf("    - Move: %5s in %.3fms    ", $bot->getMove(), $bot->getDecisionTime() * 1000)
                    . PHP_EOL;
            }
        }

        $this->consoleOutput->write(2, implode(PHP_EOL, $lines));
    }
}
