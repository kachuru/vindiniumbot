<?php

namespace spec\Kachuru\Vindinium\Game\Tile;

use Kachuru\Vindinium\Game\Position;
use Kachuru\Vindinium\Game\Tile\EmptyTile;
use Kachuru\Vindinium\Game\Tile\MineTile;
use Kachuru\Vindinium\Game\Tile\PlayerTile;
use Kachuru\Vindinium\Game\Tile\TavernTile;
use Kachuru\Vindinium\Game\Tile\Tile;
use Kachuru\Vindinium\Game\Tile\TileType;
use Kachuru\Vindinium\Game\Tile\WallTile;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class TileFactorySpec
 * @mixin \Kachuru\Vindinium\Game\Tile\TileFactory
 * @package spec\Kachuru\Vindinium
 */
class TileFactorySpec extends ObjectBehavior
{
    function it_returns_the_right_type()
    {
        $this->checkReturnType(new EmptyTile(), new Position(0, 0), '  ');

        $this->checkReturnType(new WallTile(), new Position(0, 1), '##');

        $this->checkReturnType(new MineTile(), new Position(1, 1), '$-');
        $this->checkReturnType(new MineTile(), new Position(4, 3), '$1');
        $this->checkReturnType(new MineTile(), new Position(3, 4), '$2');
        $this->checkReturnType(new MineTile(), new Position(6, 7), '$3');
        $this->checkReturnType(new MineTile(), new Position(7, 6), '$4');

        $this->checkReturnType(new PlayerTile(), new Position(1, 0), '@1');
        $this->checkReturnType(new PlayerTile(), new Position(7, 7), '@2');
        $this->checkReturnType(new PlayerTile(), new Position(2, 3), '@3');
        $this->checkReturnType(new PlayerTile(), new Position(6, 3), '@4');

        $this->checkReturnType(new TavernTile(), new Position(4, 4), '[]');
    }

    private function checkReturnType(TileType $type, Position $position, $content)
    {
        $this->buildTile($position, $content)->shouldBeLike(
            new Tile($type, $position, $content)
        );
    }
}
