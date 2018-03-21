<?php

namespace spec\Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Bot\BasicBot;
use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\BoardTile;
use Kachuru\Vindinium\Game\Player\Player;
use Kachuru\Vindinium\Game\Position;
use Kachuru\Vindinium\Game\Tile\EmptyTile;
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
        $this->beConstructedWith(new Player(1, 100, 0, 0, new Position(1, 1)));

        $board = $this->buildBoard($this->getBoardWithMines(), 5);

        $this->getMinesNotOwnedByMe($board)->shouldBeLike(
            [
                new BoardTile(new Tile(new MineTile(), '$-'), new Position(4, 1)),
                new BoardTile(new Tile(new MineTile(), '$-'), new Position(0, 3)),
                new BoardTile(new Tile(new MineTile(), '$2', 2), new Position(4, 3)),
            ]
        );

    }

    public function it_chooses_the_nearest_destination()
    {
        $this->beConstructedWith(new Player(1, 100, 0, 0, new Position(2, 0)));

        $board = $this->buildBoard($this->getBoardWithMines(), 5);

        $this->getPathToNearestAvailableMine($board)->shouldBeLike(
            [
                new BoardTile(new Tile(new EmptyTile(), '  '), new Position(2, 0)),
                new BoardTile(new Tile(new EmptyTile(), '  '), new Position(2, 1)),
                new BoardTile(new Tile(new EmptyTile(), '  '), new Position(3, 1)),
                new BoardTile(new Tile(new MineTile(), '$-'), new Position(4, 1)),
            ]
        );
    }

    public function it_gets_move()
    {
        $this->beConstructedWith(new Player(1, 100, 0, 0, new Position(2, 0)));

        $board = $this->buildBoard($this->getBoardWithMines(), 5);

        $this->chooseNextMove($board, new Position(2, 0))->shouldReturn('South');
        $this->chooseNextMove($board, new Position(2, 1))->shouldReturn('East');
        $this->chooseNextMove($board, new Position(3, 1))->shouldReturn('East');
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
