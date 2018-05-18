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

class EnhancedMapDisplay implements Display
{
    const MAX_SIZE = 118;
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

    private $bot;

    public function __construct(ConsoleOutput $consoleOutput)
    {
        $this->consoleOutput = $consoleOutput;
    }

    public function getHandle(): string
    {
        return 'enhanced-map';
    }

    public function prepare(int $boardSize)
    {
        $this->consoleOutput->prepare(
            [
                new ConsoleWindow(1, 1, self::MAX_SIZE, 1),
                new ConsoleWindow(1, 3, self::MAX_SIZE, $boardSize * 2)
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

        $this->consoleOutput->write(1, $this->renderBoard($game->getBoard(), $bot));
    }

    public function writeProgress(Game $game, Bot $bot)
    {
        $this->writeStart($game, $bot);

        $this->consoleOutput->write(1, $this->renderBoard($game->getBoard(), $bot));

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

        // $this->consoleOutput->write(2, implode(PHP_EOL, $lines));
    }

    private function renderBoard(Board $board, Bot $bot): string
    {
        return (string) implode(PHP_EOL, array_map(
            function ($row) use ($bot) {
                $rows = array_reduce($row, function ($rows, BoardTile $boardTile) use ($bot) {
                    $rows['top'][] = $this->renderTopOfBoardTile($boardTile, $bot, $boardTile->getHero());
                    $rows['bot'][] = $this->renderBottomOfBoardTile($boardTile, $boardTile->getBaseMoveCost());
                    return $rows;
                }, [
                    'top' => [],
                    'bot' => []
                ]);

                return implode(' ', $rows['top']) . '|' . PHP_EOL
                     . implode(' ', $rows['bot']) . '|';
            },
            $board->getBoardTiles()
        ));
    }

    private function renderTopOfBoardTile(BoardTile $boardTile, Bot $bot, Hero $hero = null): string
    {
        return ($boardTile->getTypeName() == 'EmptyTile' && $bot->hasPath())
            ? $this->getDisplayForEmptyTile($boardTile, $bot)
            : (string) sprintf(self::BOARD_OUTPUT[(string) $boardTile->getTypeName()], is_null($hero) ? '-' : $hero->getId());
    }

    private function renderBottomOfBoardTile(BoardTile $boardTile, int $cost = null): string
    {
        return ($boardTile->getTypeName() == 'WallTile')
            ? self::BOARD_OUTPUT[(string) $boardTile->getTypeName()]
            : sprintf('%2d', $cost);
    }

    private function getDisplayForEmptyTile(BoardTile $boardTile, Bot $bot)
    {
        return (in_array($boardTile, $bot->getPath()))
            ? '••'
            : self::BOARD_OUTPUT[(string) $boardTile->getTypeName()];
    }
}
