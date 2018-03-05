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
        $this->checkReturnType(new EmptyTile(), '  ');

        $this->checkReturnType(new WallTile(), '##');

        $this->checkReturnType(new MineTile(), '$-');
        $this->checkReturnType(new MineTile(), '$1');
        $this->checkReturnType(new MineTile(), '$2');
        $this->checkReturnType(new MineTile(), '$3');
        $this->checkReturnType(new MineTile(), '$4');

        $this->checkReturnType(new PlayerTile(), '@1');
        $this->checkReturnType(new PlayerTile(), '@2');
        $this->checkReturnType(new PlayerTile(), '@3');
        $this->checkReturnType(new PlayerTile(), '@4');

        $this->checkReturnType(new TavernTile(), '[]');
    }

    private function checkReturnType(TileType $type, $content)
    {
        $this->buildTile($content)->shouldBeLike(
            new Tile($type, $content)
        );
    }
}
