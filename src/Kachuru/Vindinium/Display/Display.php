<?php

namespace Kachuru\Vindinium\Display;

use Kachuru\Vindinium\Bot\Bot;
use Kachuru\Vindinium\Game\Game;

interface Display
{
    public function getHandle(): string;
    public function prepare(int $boardSize);
    public function writeStart(Game $game, Bot $bot);
    public function writeProgress(Game $game, Bot $bot);
}
