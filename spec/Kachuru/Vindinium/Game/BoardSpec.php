<?php

namespace spec\Kachuru\Vindinium\Game;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\BoardTile;
use Kachuru\Vindinium\Game\Position;
use Kachuru\Vindinium\Game\Tile\EmptyTile;
use Kachuru\Vindinium\Game\Tile\MineTile;
use Kachuru\Vindinium\Game\Tile\Tile;
use Kachuru\Vindinium\Game\Tile\TileFactory;
use Kachuru\Vindinium\Game\Tile\WallTile;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class BoardSpec
 * @mixin Board
 * @package spec\Kachuru\Vindinium\Game
 */
class BoardSpec extends ObjectBehavior
{
    public function it_prints_the_expected_board_output()
    {
        $this->buildBoard($this->getSimpleTestBoard(), 5);

        // Do it like this so we are not depending on checking the test board again
        $returnBoard = "          " . PHP_EOL
            . "          " . PHP_EOL
            . "  ######  " . PHP_EOL
            . "          " . PHP_EOL
            . "          " . PHP_EOL;


        $this->__toString()->shouldReturn($returnBoard);
    }

    public function it_returns_the_tile_and_given_position()
    {
        $this->buildBoard($this->getSimpleTestBoard(), 5);

        $position = new Position(0, 0);
        $this->getBoardTileAtPosition($position)->shouldBeLike(
            new BoardTile(new Tile(new EmptyTile, '  '), $position)
        );

        $position = new Position(1, 2);
        $this->getBoardTileAtPosition($position)->shouldBeLike(
            new BoardTile(new Tile(new WallTile, '##'), $position)
        );
    }

    public function it_returns_adjacent_tiles()
    {
        $this->buildBoard($this->getSimpleTestBoard(), 5);

        $this->getAdjacentBoardTiles(new Position(1, 2))->shouldBeLike(
            [
                new BoardTile(new Tile(new EmptyTile, '  '), new Position(1, 1)),
                new BoardTile(new Tile(new EmptyTile, '  '), new Position(0, 2)),
                new BoardTile(new Tile(new WallTile, '##'), new Position(2, 2)),
                new BoardTile(new Tile(new EmptyTile, '  '), new Position(1, 3)),
            ]
        );

        $this->getAdjacentBoardTiles(new Position(4, 2))->shouldBeLike(
            [
                new BoardTile(new Tile(new EmptyTile, '  '), new Position(4, 1)),
                new BoardTile(new Tile(new WallTile, '##'), new Position(3, 2)),
                new BoardTile(new Tile(new EmptyTile, '  '), new Position(4, 3)),
            ]
        );

        $this->getAdjacentBoardTiles(new Position(4, 4))->shouldBeLike(
            [
                new BoardTile(new Tile(new EmptyTile, '  '), new Position(4, 3)),
                new BoardTile(new Tile(new EmptyTile, '  '), new Position(3, 4)),
            ]
        );
    }

    public function it_returns_the_mine_tiles()
    {
        $this->buildBoard($this->getBoardWithMines(), 5);

        $this->getMineTiles()->shouldBeLike(
            [
                new BoardTile(new Tile(new MineTile, '$1', 1), new Position(0, 1)),
                new BoardTile(new Tile(new MineTile, '$-'), new Position(4, 1)),
                new BoardTile(new Tile(new MineTile, '$-'), new Position(0, 3)),
                new BoardTile(new Tile(new MineTile, '$2', 2), new Position(4, 3)),
            ]
        );
    }

    private function getSimpleTestBoard()
    {
        return "          "
             . "          "
             . "  ######  "
             . "          "
             . "          ";
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
        $this->beConstructedWith(
            new TileFactory(),
            $size,
            $boardString
        );
    }
}
