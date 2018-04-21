<?php

namespace Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\BoardTile;
use Kachuru\Vindinium\Game\Player\Player;
use Kachuru\Vindinium\Game\Position;

class BasicBot implements Bot
{
    private $currentPath;

    public function __construct()
    {
    }

    public function chooseNextMove(Board $board, Player $player): string
    {
        /**
         * Have to disable this as there is no way to check whether the current path is still valid
         */
//        if (empty($this->currentPath)) {
//            $this->currentPath = array_slice($this->getPathToNearestAvailableMine($board, $player), 1);
//        }
        if ($player->getLife() < 45) {
            $this->currentPath = array_slice($this->getPathToNearestTavern($board, $player), 1);
        } else {
            $this->currentPath = array_slice($this->getPathToNearestAvailableMine($board, $player), 1);
        }

        return $this->getNextMove($player->getPosition());
    }

    public function getPathToNearestAvailableMine(Board $board, Player $player): array
    {
        return (new PathFinder(
            $board,
            new PathEndPoints(
                $board->getBoardTileAtPosition($player->getPosition()),
                $this->getMinesNotOwnedByMe($board, $player)
            )
        ))->find();
    }

    public function getPathToNearestTavern(Board $board, Player $player): array
    {
        return (new PathFinder(
            $board,
            new PathEndPoints(
                $board->getBoardTileAtPosition($player->getPosition()),
                $board->getTavernTiles()
            )
        ))->find();
    }

    public function getMinesNotOwnedByMe(Board $board, Player $player): array
    {
        return array_values(array_filter(
            $board->getMineTiles(),
            function (BoardTile $boardTile) use ($player) {
                return $boardTile->getPlayer() != $player->getId();
            }
        ));
    }

    private function getNextMove(Position $playerPosition)
    {
        if (empty($this->currentPath)) {
            return 'Stay';
        }

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
