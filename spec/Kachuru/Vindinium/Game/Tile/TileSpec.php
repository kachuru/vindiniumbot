<?php

namespace spec\Kachuru\Vindinium\Game\Tile;

use Kachuru\Vindinium\Game\Position;
use Kachuru\Vindinium\Game\Tile\EmptyTile;
use Kachuru\Vindinium\Game\Tile\Tile;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class TileSpecSpec
 * @mixin Tile
 * @package spec\Kachuru\Vindinium\Game\Tile
 */
class TileSpec extends ObjectBehavior
{
    function it_sets_up_an_empty_tile()
    {
        $type = new EmptyTile();
        $position = new Position(1, 1);
        $this->beConstructedWith($type, $position, '  ');
        $this->getType()->shouldReturn($type);
        $this->getPosition()->shouldReturn($position);
        $this->__toString()->shouldReturn('  ');
    }
}
