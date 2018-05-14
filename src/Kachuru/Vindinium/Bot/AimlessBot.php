<?php

namespace Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\Hero\PlayerHero;

class AimlessBot implements Bot
{
    private $botHelper;
    private $move;
    private $decisionTime;

    public function __construct(BotHelper $botHelper)
    {
        $this->botHelper = $botHelper;
    }

    public function getHandle(): string
    {
        return 'aimless';
    }

    public function getName(): string
    {
        return 'AimlessBot';
    }

    public function getMove(): string
    {
        return $this->move;
    }

    public function getDecisionTime(): float
    {
        return $this->decisionTime;
    }

    public function chooseNextMove(Board $board, PlayerHero $player): string
    {
        $turnStart = microtime(true);
        $this->move = $this->botHelper->getRandomDirection();
        $this->decisionTime = microtime(true) - $turnStart;
        return $this->move;
    }
}
