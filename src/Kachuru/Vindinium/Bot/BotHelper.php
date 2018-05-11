<?php

namespace Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\Tile\BoardTile;
use Kachuru\Vindinium\Game\Hero\EnemyHero;
use Kachuru\Vindinium\Game\Hero\PlayerHero;
use Kachuru\Vindinium\Game\Position;

class BotHelper
{
    const DIRECTION_STAY = 'Stay';
    const DIRECTION_NORTH = 'North';
    const DIRECTION_EAST = 'East';
    const DIRECTION_WEST = 'West';
    const DIRECTION_SOUTH = 'South';

    const DIRECTIONS = [
        self::DIRECTION_STAY,
        self::DIRECTION_NORTH,
        self::DIRECTION_EAST,
        self::DIRECTION_SOUTH,
        self::DIRECTION_WEST
    ];

    const COORD_DIRECTIONS = [
        -1 => [
            0 => self::DIRECTION_EAST
        ],
        0 => [
            -1 => self::DIRECTION_SOUTH,
            0 => self::DIRECTION_STAY,
            1 => self::DIRECTION_NORTH,
        ],
        1 => [
            0 => self::DIRECTION_WEST
        ]
    ];

    public function getRandomDirection(): string
    {
        return self::DIRECTIONS[mt_rand(0, count(self::DIRECTIONS) - 1)];
    }

    public function getRelativeDirection(Position $from, Position $to): string
    {
        return self::COORD_DIRECTIONS
            [$this->getVector($from->getX(), $to->getX())]
            [$this->getVector($from->getY(), $to->getY())];
    }

    public function getPathToNearestAvailableMine(Board $board, PlayerHero $player): array
    {
        return $this->getPathToNearestDestination(
            $board,
            $board->getBoardTileAtPosition($player->getPosition()),
            $this->getMinesNotOwnedByPlayerHero($board)
        );
    }

    public function getPathToNearestTavern(Board $board, PlayerHero $player): array
    {
        return $this->getPathToNearestDestination(
            $board,
            $board->getBoardTileAtPosition($player->getPosition()),
            $board->getTavernTiles()
        );
    }

    private function getPathToNearestDestination(Board $board, BoardTile $origin, $destinations): array
    {
        return array_slice(
            (new PathFinder($board, new PathEndPoints($origin, $destinations)))->find(),
            1
        );
    }

    public function getMinesNotOwnedByPlayerHero(Board $board): array
    {
        return array_values(array_filter(
            $board->getMineTiles(),
            function (BoardTile $boardTile) {
                return is_null($boardTile->getHero()) || $boardTile->getHero() instanceof EnemyHero;
            }
        ));
    }

    private function getVector($from, $to)
    {
        $vector = $from - $to;

        if ($vector != 0) {
            $vector = $vector / abs($vector);
        }

        return $vector;
    }
}
