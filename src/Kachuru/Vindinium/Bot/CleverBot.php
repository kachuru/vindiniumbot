<?php

namespace Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\Tile\BoardTile;
use Kachuru\Vindinium\Game\Hero\PlayerHero;
use Kachuru\Vindinium\Game\Position;
use Kachuru\Vindinium\Game\Tile\EnemyHeroTile;
use Kachuru\Vindinium\Game\Tile\MineTile;

class CleverBot implements Bot
{
    const TAVERN_LIFE = 35;

    private $currentPath;
    private $previousPosition;
    /**
     * @var BotHelper
     */
    private $botHelper;
    private $move;
    private $decisionTime;

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

    public function getMove(): string
    {
        return $this->move;
    }

    public function getDecisionTime(): float
    {
        return $this->decisionTime;
    }

    public function hasPath(): bool
    {
        return !empty($this->currentPath);
    }

    public function getPath(): array
    {
        return $this->currentPath;
    }

    public function chooseNextMove(Board $board, PlayerHero $player): string
    {
        $turnStart = microtime(true);

        if (!$this->validatePosition($player->getPosition()) || !$this->validatePath($board, $player)) {
            $this->currentPath = null;
        }

        if (empty($this->currentPath)) {
            $this->currentPath = $this->selectPath($board, $player);
        }

        if (empty($this->currentPath)) {
            return BotHelper::DIRECTION_STAY;
        }

        $this->move = $this->botHelper->getRelativeDirection(
            $player->getPosition(),
            array_shift($this->currentPath)->getPosition()
        );

        $this->decisionTime = microtime(true) - $turnStart;

        return $this->move;
    }

    public function selectPath(Board $board, PlayerHero $player): array
    {
        if ($player->getLife() < self::TAVERN_LIFE) {
            $path = $this->botHelper->getPathToNearestTavern($board, $player);
        } else {
            $path = $this->botHelper->getPathToNearestAvailableMine($board, $player);
        }

        return $path;
    }

    private function validatePath(Board $board, PlayerHero $player): bool
    {
        if (!empty($this->currentPath)) {
            // Ensure we have enough life to take the mine
            if ($this->destinationIsAMine() && !$this->enoughLifeToCaptureMine($player)) {
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

    private function destinationIsAMine(): bool
    {
        return $this->getDestination()->getTileType() instanceOf MineTile;
    }

    private function enoughLifeToCaptureMine(PlayerHero $player): bool
    {
        return $player->getLife() < (count($this->currentPath) + 21);
    }
}
