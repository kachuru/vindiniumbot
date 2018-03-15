<?php

namespace Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Game\Game;

interface Bot
{
    public function move(Game $game): string;
}
