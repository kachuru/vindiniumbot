<?php

namespace spec\Kachuru\Vindinium\Game\Tile;

use Kachuru\Vindinium\Game\Hero\BaseHero;
use Kachuru\Vindinium\Game\Position;
use Kachuru\Vindinium\Game\Tile\EmptyTile;
use Kachuru\Vindinium\Game\Tile\BoardTile;
use Kachuru\Vindinium\Game\Tile\MineTile;
use Kachuru\Vindinium\Game\Tile\EnemyHeroTile;
use Kachuru\Vindinium\Game\Tile\TavernTile;
use Kachuru\Vindinium\Game\Tile\WallTile;
use PhpSpec\ObjectBehavior;

/**
 * Class BoardTileSpec
 * @mixin BoardTile
 * @package spec\Kachuru\Vindinium\Game\Tile
 */
class BoardTileSpec extends ObjectBehavior
{
    function it_sets_up_a_wall_tile()
    {
        $position = new Position(5, 7);
        $type = new WallTile();

        $this->beConstructedWith($position, $type);
        $this->getPosition()->shouldReturn($position);
        $this->getTileType()->shouldReturn($type);
        $this->getTypeName()->shouldReturn('WallTile');
        $this->getHero()->shouldReturn(null);
        $this->__toString()->shouldReturn('##');
        $this->isWalkable()->shouldReturn(false);
        $this->getBaseMoveCost()->shouldReturn(null);
    }

    function it_sets_up_an_empty_tile()
    {
        $position = new Position(2, 1);
        $type = new EmptyTile();

        $this->beConstructedWith($position, $type);
        $this->getPosition()->shouldReturn($position);
        $this->getTileType()->shouldReturn($type);
        $this->getTypeName()->shouldReturn('EmptyTile');
        $this->getHero()->shouldReturn(null);
        $this->__toString()->shouldReturn('  ');
        $this->isWalkable()->shouldReturn(true);
        $this->getBaseMoveCost()->shouldReturn(1);
    }

    function it_sets_up_a_mine_tile_without_player()
    {
        $position = new Position(4, 2);
        $type = new MineTile();

        $this->beConstructedWith($position, $type);
        $this->getPosition()->shouldReturn($position);
        $this->getTileType()->shouldReturn($type);
        $this->getTypeName()->shouldReturn('MineTile');
        $this->getHero()->shouldReturn(null);
        $this->__toString()->shouldReturn('$-');
        $this->isWalkable()->shouldReturn(false);
        $this->getBaseMoveCost()->shouldReturn(1);
    }

    function it_sets_up_a_mine_tile_with_player()
    {
        $position = new Position(2, 2);
        $type = new MineTile();
        $hero = new BaseHero(1, 'Random', 100, 0, 0, new Position(1, 1));

        $this->beConstructedWith($position, $type, $hero);
        $this->getPosition()->shouldReturn($position);
        $this->getTileType()->shouldReturn($type);
        $this->getTypeName()->shouldReturn('MineTile');
        $this->getHero()->shouldReturn($hero);
        $this->__toString()->shouldReturn('$1');
        $this->isWalkable()->shouldReturn(false);
        $this->getBaseMoveCost()->shouldReturn(1);
    }

    function it_sets_up_a_tavern_tile()
    {
        $position = new Position(5, 8);
        $type = new TavernTile();

        $this->beConstructedWith($position, $type);
        $this->getPosition()->shouldReturn($position);
        $this->getTileType()->shouldReturn($type);
        $this->getTypeName()->shouldReturn('TavernTile');
        $this->getHero()->shouldReturn(null);
        $this->__toString()->shouldReturn('[]');
        $this->isWalkable()->shouldReturn(false);
        $this->getBaseMoveCost()->shouldReturn(1);
    }

    function it_sets_up_a_player_tile()
    {
        $position = new Position(4, 8);
        $type = new EnemyHeroTile();
        $hero = new BaseHero(4, 'Random', 100, 0, 0, new Position(4, 4));

        $this->beConstructedWith($position, $type, $hero);
        $this->getPosition()->shouldReturn($position);
        $this->getTileType()->shouldReturn($type);
        $this->getTypeName()->shouldReturn('EnemyHeroTile');
        $this->getHero()->shouldReturn($hero);
        $this->__toString()->shouldReturn('@4');
        $this->isWalkable()->shouldReturn(true);
        $this->getBaseMoveCost()->shouldReturn(5);
    }
}
