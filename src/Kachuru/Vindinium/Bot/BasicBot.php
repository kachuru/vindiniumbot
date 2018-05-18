<?php

namespace Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\Hero\PlayerHero;

class BasicBot implements Bot
{
    const TAVERN_LIFE = 35;

    private $currentPath;
    private $botHelper;
    private $decisionTime;
    private $move;

    public function __construct(BotHelper $botHelper)
    {
        $this->botHelper = $botHelper;
    }

    public function getHandle(): string
    {
        return 'basic';
    }

    public function getName(): string
    {
        return 'BasicBot';
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
        return isset($this->currentPath);
    }

    public function getPath(): array
    {
        return $this->currentPath;
    }

    public function chooseNextMove(Board $board, PlayerHero $player): string
    {
        $turnStart = microtime(true);

        if ($player->getLife() < self::TAVERN_LIFE) {
            $this->currentPath = $this->botHelper->getPathToNearestTavern($board, $player);
        } else {
            $this->currentPath = $this->botHelper->getPathToNearestAvailableMine($board, $player);
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
}
