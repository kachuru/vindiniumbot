<?php

namespace Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\Hero\PlayerHero;

class AimlessBot implements Bot
{
    private $botHelper;

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

    public function chooseNextMove(Board $board, PlayerHero $player): string
    {
        return $this->botHelper->getRandomDirection();
    }
}
