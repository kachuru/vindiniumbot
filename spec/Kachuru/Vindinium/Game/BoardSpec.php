<?php

namespace spec\Kachuru\Vindinium\Game;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\BoardTile;
use Kachuru\Vindinium\Game\Hero\BaseHero;
use Kachuru\Vindinium\Game\Hero\Heroes;
use Kachuru\Vindinium\Game\Position;
use Kachuru\Vindinium\Game\Tile\EmptyTile;
use Kachuru\Vindinium\Game\Tile\MineTile;
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
    private $heroes;
    private $tileFactory;

    public function let()
    {
        $this->heroes = new Heroes([
            new BaseHero(1, 'HeroOne', 100, 0, 0, new Position(0, 0)),
            new BaseHero(2, 'HeroTwo', 100, 0, 0, new Position(4, 0)),
            new BaseHero(3, 'HeroThree', 100, 0, 0, new Position(0, 4)),
            new BaseHero(4, 'HeroFour', 100, 0, 0, new Position(4, 4)),
        ]);

        $this->tileFactory = new TileFactory($this->heroes);
    }

    public function it_prints_the_expected_board_output()
    {
        $this->beConstructedWith($this->tileFactory, 5, $this->getSimpleTestBoard());

        // Do it like this so we are not depending on checking the test board again
        $returnBoard =
              "          " . PHP_EOL
            . "          " . PHP_EOL
            . "  ######  " . PHP_EOL
            . "          " . PHP_EOL
            . "          ";


        $this->__toString()->shouldReturn($returnBoard);
    }

    public function it_returns_the_tile_and_given_position()
    {
        $this->beConstructedWith($this->tileFactory, 5, $this->getSimpleTestBoard());

        $position = new Position(0, 0);
        $this->getBoardTileAtPosition($position)->shouldBeLike(
            new BoardTile($position, new EmptyTile)
        );

        $position = new Position(1, 2);
        $this->getBoardTileAtPosition($position)->shouldBeLike(
            new BoardTile($position, new WallTile)
        );
    }

    public function it_returns_adjacent_tiles()
    {
        $this->beConstructedWith($this->tileFactory, 5, $this->getSimpleTestBoard());

        $this->getAdjacentBoardTiles(new Position(1, 2))->shouldBeLike(
            [
                new BoardTile(new Position(1, 1), new EmptyTile()),
                new BoardTile(new Position(0, 2), new EmptyTile()),
                new BoardTile(new Position(2, 2), new WallTile()),
                new BoardTile(new Position(1, 3), new EmptyTile()),
            ]
        );

        $this->getAdjacentBoardTiles(new Position(4, 2))->shouldBeLike(
            [
                new BoardTile(new Position(4, 1), new EmptyTile()),
                new BoardTile(new Position(3, 2), new WallTile()),
                new BoardTile(new Position(4, 3), new EmptyTile()),
            ]
        );

        $this->getAdjacentBoardTiles(new Position(4, 4))->shouldBeLike(
            [
                new BoardTile(new Position(4, 3), new EmptyTile()),
                new BoardTile(new Position(3, 4), new EmptyTile()),
            ]
        );
    }

    public function it_returns_the_mine_tiles()
    {
        $this->beConstructedWith($this->tileFactory, 5, $this->getBoardWithMines());

        $this->getMineTiles()->shouldBeLike(
            [
                new BoardTile(new Position(0, 1), new MineTile(), $this->heroes->getHero(1)),
                new BoardTile(new Position(4, 1), new MineTile()),
                new BoardTile(new Position(0, 3), new MineTile()),
                new BoardTile(new Position(4, 3), new MineTile(), $this->heroes->getHero(2))
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

    private function getM1Board()
    {
        return "##@1    ####    @4##"
            . "      ########      "
            . "        ####        "
            . "    []        []    "
            . "$-    ##    ##    $-"
            . "$-    ##    ##    $-"
            . "    []        []    "
            . "        ####  @3    "
            . "      ########      "
            . "##@2    ####      ##";
    }
}
