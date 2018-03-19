<?php

namespace spec\Kachuru\Vindinium\Game\Tile;

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
        $this->checkReturnType(new EmptyTile(), '  ', 0);

        $this->checkReturnType(new WallTile(), '##', 0);

        $this->checkReturnType(new MineTile(), '$-', 0);
        $this->checkReturnType(new MineTile(), '$1', 1);
        $this->checkReturnType(new MineTile(), '$2', 2);
        $this->checkReturnType(new MineTile(), '$3', 3);
        $this->checkReturnType(new MineTile(), '$4', 4);

        $this->checkReturnType(new PlayerTile(), '@1', 1);
        $this->checkReturnType(new PlayerTile(), '@2', 2);
        $this->checkReturnType(new PlayerTile(), '@3', 3);
        $this->checkReturnType(new PlayerTile(), '@4', 4);

        $this->checkReturnType(new TavernTile(), '[]', 0);
    }

    private function checkReturnType(TileType $type, $content, $player)
    {
        $this->buildTile($content)->shouldBeLike(
            new Tile($type, $content, $player)
        );
    }
}
