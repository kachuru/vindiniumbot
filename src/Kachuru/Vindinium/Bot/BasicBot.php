<?php

namespace Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\BoardTile;
use Kachuru\Vindinium\Game\Hero\PlayerHero as Player;
use Kachuru\Vindinium\Game\Position;

class BasicBot implements Bot
{
    const TAVERN_LIFE = 35;

    private $currentPath;
    private $botHelper;

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

    public function chooseNextMove(Board $board, Player $player): string
    {
        if ($player->getLife() < self::TAVERN_LIFE) {
            $this->currentPath = array_slice($this->getPathToNearestTavern($board, $player), 1);
        } else {
            $this->currentPath = array_slice($this->getPathToNearestAvailableMine($board, $player), 1);
        }

        if (empty($this->currentPath)) {
            return BotHelper::DIRECTION_STAY;
        }

        return $this->botHelper->getRelativeDirection(
            $player->getPosition(),
            array_shift($this->currentPath)->getPosition()
        );
    }

    public function getPathToNearestAvailableMine(Board $board, Player $player): array
    {
        return (new PathFinder(
            $board,
            new PathEndPoints(
                $board->getBoardTileAtPosition($player->getPosition()),
                $this->botHelper->getMinesNotOwnedByPlayerHero($board)
            )
        ))->find();
    }

    public function getPathToNearestTavern(Board $board, Player $player): array
    {
        return (new PathFinder(
            $board,
            new PathEndPoints(
                $board->getBoardTileAtPosition($player->getPosition()),
                $board->getTavernTiles()
            )
        ))->find();
    }
}
