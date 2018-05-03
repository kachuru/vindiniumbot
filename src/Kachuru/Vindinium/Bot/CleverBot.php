<?php

namespace Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\BoardTile;
use Kachuru\Vindinium\Game\Hero\EnemyHero;
use Kachuru\Vindinium\Game\Hero\PlayerHero;
use Kachuru\Vindinium\Game\Position;
use Kachuru\Vindinium\Game\Tile\EnemyHeroTile;

class CleverBot implements Bot
{
    const TAVERN_LIFE = 35;

    private $currentPath;
    private $previousPosition;
    /**
     * @var BotHelper
     */
    private $botHelper;

    public function __construct(BotHelper $botHelper)
    {
        $this->botHelper = $botHelper;
    }

    // TO DO
    // 1. Affect adjacent square move costs.
    // 6. Configurable things

    public function getHandle(): string
    {
        return 'clever';
    }

    public function getName(): string
    {
        return 'CleverBot';
    }

    public function chooseNextMove(Board $board, PlayerHero $player): string
    {
        if (!$this->validatePosition($player->getPosition()) || !$this->validatePath($board, $player)) {
            $this->currentPath = null;
        }

        if (empty($this->currentPath)) {
            $this->currentPath = $this->selectPath($board, $player);
        }

        if (empty($this->currentPath)) {
            return BotHelper::DIRECTION_STAY;
        }

        return $this->botHelper->getRelativeDirection(
            $player->getPosition(),
            array_shift($this->currentPath)->getPosition()
        );
    }

    public function selectPath(Board $board, PlayerHero $player): array
    {
        if ($player->getLife() < self::TAVERN_LIFE) {
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
            $this->getMinesNotOwnedByMe($board)
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

    private function validatePath(Board $board, PlayerHero $player): bool
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

                $checkTiles = [$boardTile];
                if (!$this->isDestination($boardTile)) {
                    $checkTiles = array_merge($checkTiles, $board->getAdjacentBoardTiles($boardTile->getPosition()));
                }

                foreach ($checkTiles as $tile) {
                    if ($tile->getTileType() instanceof EnemyHeroTile
                        && $tile->getHero()->getLife() < $player->getLife()) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    private function validatePosition(Position $position): bool
    {
        return isset($this->previousPosition) && $this->previousPosition == $position;
    }

    private function isDestination($boardTile)
    {
        return $this->getDestination() == $boardTile;
    }

    private function getDestination(): ?BoardTile
    {
        if (!empty($this->currentPath)) {
            return end($this->currentPath);
        }
    }
}
