<?php

namespace Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\BoardTile;
use Kachuru\Vindinium\Game\Hero\EnemyHero;
use Kachuru\Vindinium\Game\Hero\PlayerHero;
use Kachuru\Vindinium\Game\Position;
use Kachuru\Vindinium\Game\Tile\EnemyHeroTile;

class BasicBot implements Bot
{
    private $currentPath;
    private $lastPosition;

    // TO DO
    // 1. Awareness of nearby tiles
    // 2. Can you make it to the next mine?
    // 4. If a bot is in the way, can you take it in a fight?
    // 5. RUN AWAY
    // 6. Configurable things

    public function chooseNextMove(Board $board, PlayerHero $player): string
    {
        if (!$this->validatePosition($player->getPosition()) || !$this->validatePath($player)) {
            $this->currentPath = null;
        }

        if (empty($this->currentPath)) {
            $this->currentPath = $this->selectPath($board, $player);
        }

        return $this->getNextMove($player->getPosition());
    }

    public function selectPath(Board $board, PlayerHero $player): array
    {
        if ($player->getLife() < 45) {
            $path = $this->getPathToNearestTavern($board, $player);
        } else {
            $path = $this->getPathToNearestAvailableMine($board, $player);
        }

        return $path;
    }

    public function getPathToNearestAvailableMine(Board $board, PlayerHero $player): array
    {
        return $this->getPathToNearestDestination(
            $board,
            $board->getBoardTileAtPosition($player->getPosition()),
            $this->getMinesNotOwnedByMe($board, $player)
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

    private function getPathToNearestDestination(Board $board, $origin, $destinations): array
    {
        return array_slice(
            (new PathFinder($board, new PathEndPoints($origin, $destinations)))->find(),
            1
        );
    }

    public function getMinesNotOwnedByMe(Board $board): array
    {
        return array_values(array_filter(
            $board->getMineTiles(),
            function (BoardTile $boardTile) {
                return is_null($boardTile->getHero()) || $boardTile->getHero() instanceof EnemyHero;
            }
        ));
    }

    private function validatePath(PlayerHero $player): bool
    {
        if (!empty($this->currentPath)) {
            // Ensure we have enough life to take the mine
            if ($player->getLife() < count($this->currentPath) + 21) {
                return false;
            }

            foreach ($this->currentPath as $boardTile) {
                /**
                 * @var BoardTile $boardTile
                 */
                // This doesn't work because you need to check if any of the adjacent tiles contain an enemy player too
                if ($boardTile->getTileType() instanceof EnemyHeroTile) {
                    // Cowardly run away
                    // - work out if you can beat it in a fight
                    return false;
                }
            }
        }

        return true;
    }

    private function validatePosition(Position $position): bool
    {
        return isset($this->lastPosition) && $this->lastPosition == $position;
    }

    private function getNextMove(Position $playerPosition)
    {
        if (empty($this->currentPath)) {
            return 'Stay';
        }

        // $playerPosition = $this->player->getPosition();
        $nextTile = array_shift($this->currentPath);
        $nextPosition = $nextTile->getPosition();

        $this->lastPosition = $nextPosition;

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
