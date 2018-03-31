<?php

namespace Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\Hero\PlayerHero;

interface Bot
{
    public function chooseNextMove(Board $board, PlayerHero $player): string;
}
