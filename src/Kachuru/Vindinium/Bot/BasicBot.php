<?php

namespace Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\BoardTile;
use Kachuru\Vindinium\Game\Hero\PlayerHero as Player;
use Kachuru\Vindinium\Game\Position;

class BasicBot implements Bot
{
    const TAVERN_LIFE = 35;

    private $currentPath;

    public function getHandle(): string
    {
        return 'basic';
    }

    public function chooseNextMove(Board $board, Player $player): string
    {
        if ($player->getLife() < self::TAVERN_LIFE) {
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
                return $boardTile->getHero() != $player->getId();
            }
        ));
    }

    private function getNextMove(Position $playerPosition)
    {
        if (empty($this->currentPath)) {
            return 'Stay';
        }

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
