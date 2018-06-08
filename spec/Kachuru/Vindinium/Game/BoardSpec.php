<?php

namespace spec\Kachuru\Vindinium\Game;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\Tile\BaseBoardTile;
use Kachuru\Vindinium\Game\Position;
use Kachuru\Vindinium\Game\Tile\Type\EmptyTile;
use Kachuru\Vindinium\Game\Tile\Type\MineTile;
use Kachuru\Vindinium\Game\Tile\TileFactory;
use Kachuru\Vindinium\Game\Tile\Type\WallTile;
use PhpSpec\ObjectBehavior;
use spec\Kachuru\Vindinium\SpecHelper;

/**
 * Class BoardSpec
 * @mixin Board
 * @package spec\Kachuru\Vindinium\Game
 */
class BoardSpec extends ObjectBehavior
{
    private $heroes;
    private $tileFactory;

    public function __construct()
    {
        $this->specHelper = new SpecHelper();
    }

    public function let()
    {
        $this->heroes = $this->specHelper->getHeroes();

        $this->tileFactory = new TileFactory($this->heroes);
    }

    public function it_returns_the_tile_and_given_position()
    {
        $this->beConstructedWith($this->tileFactory, 5, $this->getSimpleTestBoard());

        $position = new Position(0, 0);
        $this->getBoardTileAtPosition($position)->shouldBeLike(
            new BaseBoardTile($position, new EmptyTile)
        );

        $position = new Position(1, 2);
        $this->getBoardTileAtPosition($position)->shouldBeLike(
            new BaseBoardTile($position, new WallTile)
        );
    }

    public function it_returns_adjacent_tiles()
    {
        $this->beConstructedWith($this->tileFactory, 5, $this->getSimpleTestBoard());

        $this->getAdjacentBoardTiles(new Position(1, 2))->shouldBeLike(
            [
                new BaseBoardTile(new Position(1, 1), new EmptyTile()),
                new BaseBoardTile(new Position(0, 2), new EmptyTile()),
                new BaseBoardTile(new Position(2, 2), new WallTile()),
                new BaseBoardTile(new Position(1, 3), new EmptyTile()),
            ]
        );

        $this->getAdjacentBoardTiles(new Position(4, 2))->shouldBeLike(
            [
                new BaseBoardTile(new Position(4, 1), new EmptyTile()),
                new BaseBoardTile(new Position(3, 2), new WallTile()),
                new BaseBoardTile(new Position(4, 3), new EmptyTile()),
            ]
        );

        $this->getAdjacentBoardTiles(new Position(4, 4))->shouldBeLike(
            [
                new BaseBoardTile(new Position(4, 3), new EmptyTile()),
                new BaseBoardTile(new Position(3, 4), new EmptyTile()),
            ]
        );
    }

    public function it_returns_the_mine_tiles()
    {
        $this->beConstructedWith($this->tileFactory, 5, $this->getBoardWithMines());

        $this->getMineTiles()->shouldBeLike(
            [
                new BaseBoardTile(new Position(0, 1), new MineTile(), $this->heroes->getHero(1)),
                new BaseBoardTile(new Position(4, 1), new MineTile()),
                new BaseBoardTile(new Position(0, 3), new MineTile()),
                new BaseBoardTile(new Position(4, 3), new MineTile(), $this->heroes->getHero(2))
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
}
