<?php

namespace spec\Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Bot\BasicBot;
use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\BoardTile;
use Kachuru\Vindinium\Game\Player\Player;
use Kachuru\Vindinium\Game\Position;
use Kachuru\Vindinium\Game\Tile\MineTile;
use Kachuru\Vindinium\Game\Tile\Tile;
use Kachuru\Vindinium\Game\Tile\TileFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class BasicBotSpec
 * @mixin BasicBot
 * @package spec\Kachuru\Vindinium\Bot
 */
class BasicBotSpec extends ObjectBehavior
{
    public function it_finds_available_destinations()
    {
        $this->beConstructedWith(new Player(1, 100, 0, 0, new Position(0, 0)));

        $board = $this->buildBoard($this->getBoardWithMines(), 5);

        $this->chooseDestinations($board)->shouldBeLike(
            [
                new BoardTile(new Tile(new MineTile(), '$-'), new Position(4, 1)),
                new BoardTile(new Tile(new MineTile(), '$-'), new Position(0, 3)),
                new BoardTile(new Tile(new MineTile(), '$2', 2), new Position(4, 3)),
            ]
        );

    }

    private function getBoardWithMines()
    {
        return "          "
            . "$1      $-"
            . "          "
            . "$-      $2"
            . "          ";
    }

    private function buildBoard(string $boardString, int $size)
    {
        return new Board(
            new TileFactory(),
            $size,
            $boardString
        );
    }
}
