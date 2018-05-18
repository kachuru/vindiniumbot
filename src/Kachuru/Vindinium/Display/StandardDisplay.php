<?php

namespace Kachuru\Vindinium\Display;

use Kachuru\Util\ConsoleOutput;
use Kachuru\Util\ConsoleWindow;
use Kachuru\Vindinium\Bot\Bot;
use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\Game;
use Kachuru\Vindinium\Game\Hero\Hero;
use Kachuru\Vindinium\Game\Hero\PlayerHero;
use Kachuru\Vindinium\Game\Tile\BoardTile;

class StandardDisplay implements Display
{
    const MAX_SIZE = 117;

    const HERO_OUTPUT = ' [%d] %s [%2d, %2d] - Life:%3d Gold: %4d Mines: %2d';
    const BOT_STATUS_OUTPUT = "    - Move: %5s in %.3fms    ";

    const BOARD_OUTPUT = [
        'EmptyTile' => '  ',
        'WallTile' => '##',
        'MineTile' => '$%s',
        'TavernTile' => '[]',
        'EnemyHeroTile' => '@%d',
        'PlayerHeroTile' => '@%d'
    ];

    /**
     * @var ConsoleOutput
     */
    private $consoleOutput;

    public function __construct(ConsoleOutput $consoleOutput)
    {
        $this->consoleOutput = $consoleOutput;
    }

    public function getHandle(): string
    {
        return 'standard';
    }

    public function prepare(int $boardSize)
    {
        $this->consoleOutput->prepare(
            [
                new ConsoleWindow(1, 1, self::MAX_SIZE + 1, 1),
                new ConsoleWindow(1, 3, $boardSize * 2, $boardSize),
                new ConsoleWindow(($boardSize * 2) + 2, 3, self::MAX_SIZE - ($boardSize * 2), $boardSize)
            ]
        );
    }

    public function writeStart(Game $game, Bot $bot)
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

        $this->consoleOutput->write(1, $this->renderBoard($game->getBoard()));
    }

    public function writeProgress(Game $game, Bot $bot)
    {
        $this->writeStart($game, $bot);

        $this->consoleOutput->write(1, $this->renderBoard($game->getBoard()));

        $lines = [];
        foreach ($game->getHeroes() as $hero) {
            $lines[] = sprintf(
                self::HERO_OUTPUT,
                $hero->getId(),
                str_pad(substr($hero->getName(), 0, 13), 13),
                $hero->getPosition()->getX(),
                $hero->getPosition()->getY(),
                $hero->getLife(),
                $hero->getGold(),
                $hero->getMineCount()
            );

            if ($hero instanceof PlayerHero) {
                $lines[] = sprintf(
                        self::BOT_STATUS_OUTPUT,
                        $bot->getMove(),
                        $bot->getDecisionTime() * 1000
                    ) . PHP_EOL;
            }
        }

        $this->consoleOutput->write(2, implode(PHP_EOL, $lines));
    }

    private function renderBoard(Board $board): string
    {
        return (string) implode(PHP_EOL, array_map(
            function ($row) {
                return implode('', array_map(
                    function (BoardTile $boardTile) {
                        return $this->renderBoardTile($boardTile->getTypeName(), $boardTile->getHero());
                    },
                    $row
                ));
            },


            $board->getBoardTiles()
        ));
    }

    private function renderBoardTile(string $tileType, Hero $hero = null): string
    {
        return (string) sprintf(self::BOARD_OUTPUT[$tileType], is_null($hero) ? '-' : $hero->getId());
    }
}
