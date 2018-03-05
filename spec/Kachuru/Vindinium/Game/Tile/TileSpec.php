<?php

namespace spec\Kachuru\Vindinium\Game\Tile;

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
        $this->beConstructedWith($type, '  ');
        $this->getType()->shouldReturn($type);
        $this->__toString()->shouldReturn('  ');
    }
}
