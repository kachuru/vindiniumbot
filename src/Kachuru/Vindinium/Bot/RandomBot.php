<?php

namespace Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\Hero\PlayerHero;

class RandomBot implements Bot
{
    /**
     * @var Bot
     */
    private $bot;
    /**
     * @var BotFactory
     */
    private $botFactory;

    public function __construct(BotFactory $botFactory)
    {
        $this->botFactory = $botFactory;
    }

    public function getHandle(): string
    {
        return 'random';
    }

    public function getName(): string
    {
        return 'Random ' . $this->getBot()->getName();
    }

    public function getMove(): string
    {
        return $this->bot->getMove();
    }

    public function getPath(): array
    {
        return $this->bot->getPath();
    }

    public function hasPath(): bool
    {
        return $this->bot->hasPath();
    }

    public function getDecisionTime(): float
    {
        return $this->bot->getDecisionTime();
    }

    public function chooseNextMove(Board $board, PlayerHero $player): string
    {
        return $this->getBot()->chooseNextMove($board, $player);
    }

    private function getBot(): Bot
    {
        if (is_null($this->bot)) {
            $bots = $this->botFactory->getAllBots();
            $botKeys = array_diff(array_keys($bots), [$this->getHandle()]);
            $this->bot = $bots[$botKeys[array_rand($botKeys)]];
        }
        return $this->bot;
    }
}
