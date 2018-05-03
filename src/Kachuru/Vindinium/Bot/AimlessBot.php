<?php

namespace Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\Hero\PlayerHero;

class AimlessBot implements Bot
{
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
        $directions = ['Stay', 'North', 'East', 'South', 'West'];

        return $directions[mt_rand(0, count($directions) - 1)];
    }
}
