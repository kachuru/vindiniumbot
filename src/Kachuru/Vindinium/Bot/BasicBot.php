<?php

namespace Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\BoardTile;
use Kachuru\Vindinium\Game\Player\Player;
use Kachuru\Vindinium\Game\Position;

class BasicBot implements Bot
{
    private $currentPath;
    private $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
    }

    public function move(Board $board, Position $position): string
    {
        if (empty($this->currentPath)) {
            $this->currentPath = array_slice($this->getPathToNearestAvailableMine($board), 1);
        }

        return $this->getNextMove($position);
    }

    public function getPathToNearestAvailableMine(Board $board)
    {
        return (new PathFinder(
            $board,
            new PathEndPoints(
                $board->getBoardTileAtPosition($this->player->getPosition()),
                $this->getMinesNotOwnedByMe($board)
            )
        ))->find();
    }

    public function getMinesNotOwnedByMe(Board $board)
    {
        return array_values(array_filter(
            $board->getMineTiles(),
            function (BoardTile $boardTile) {
                return $boardTile->getPlayer() != $this->player->getId();
            }
        ));
    }

    private function getNextMove(Position $playerPosition)
    {
        // $playerPosition = $this->player->getPosition();
        $nextTile = array_shift($this->currentPath);
        $nextPosition = $nextTile->getPosition();

        if ($playerPosition->getX() > $nextPosition->getX()) {
            return 'West';
        }

        if ($playerPosition->getY() > $nextPosition->getY()) {
            return 'North';
        }

        if ($playerPosition->getY() < $nextPosition->getY()) {
            return 'South';
        }

        if ($playerPosition->getX() < $nextPosition->getX()) {
            return 'East';
        }

        return 'Stay';
    }
}
