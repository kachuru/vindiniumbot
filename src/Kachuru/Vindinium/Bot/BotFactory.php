<?php

namespace Kachuru\Vindinium\Bot;


class BotFactory
{
    private $bots = [];

    public function addBot(Bot $bot)
    {
        $this->bots[$bot->getHandle()] = $bot;
    }

    public function getBotByHandle($handle): Bot
    {
        return $this->bots[$handle];
    }

    public function getAllBots()
    {
        return $this->bots;
    }
}
