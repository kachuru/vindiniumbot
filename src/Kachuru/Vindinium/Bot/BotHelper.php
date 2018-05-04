<?php

namespace Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\BoardTile;
use Kachuru\Vindinium\Game\Hero\EnemyHero;
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

    public function getRandomDirection(): string
    {
        return self::DIRECTIONS[mt_rand(0, count(self::DIRECTIONS) - 1)];
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

    public function getRelativeDirection(Position $from, Position $to): string
    {
        if ($from->getX() > $to->getX()) {
            return self::DIRECTION_WEST;
        }

        if ($from->getY() > $to->getY()) {
            return self::DIRECTION_NORTH;
        }

        if ($from->getY() < $to->getY()) {
            return self::DIRECTION_SOUTH;
        }

        if ($from->getX() < $to->getX()) {
            return self::DIRECTION_EAST;
        }

        return self::DIRECTION_STAY;
    }
}
