<?php

namespace Kachuru\Vindinium\Display;

use Kachuru\Vindinium\Bot\Bot;
use Kachuru\Vindinium\Game\Game;

interface Display
{
    public function getHandle(): string;
    public function prepare(int $boardSize);
    public function write(Game $game, Bot $bot);
}
