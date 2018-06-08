<?php

namespace spec\Kachuru\Vindinium;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\Hero\BaseHero;
use Kachuru\Vindinium\Game\Hero\EnemyHero;
use Kachuru\Vindinium\Game\Hero\Heroes;
use Kachuru\Vindinium\Game\Hero\PlayerHero;
use Kachuru\Vindinium\Game\Position;
use Kachuru\Vindinium\Game\Tile\TileFactory;

class SpecHelper
{
    public function buildSimpleWallBoard(Heroes $heroes): Board
    {
        return $this->buildBoard(
            "          " .
            "          " .
            "  ######  " .
            "          " .
            "          ",
            5,
            $heroes
        );
    }

    public function buildMinesBoard(Heroes $heroes): Board
    {
        return $this->buildBoard(
            "          " .
            "$1      $-" .
            "          " .
            "$-      $2" .
            "          ",
            5,
            $heroes
        );
    }

    public function buildPlayersBoard(Heroes $heroes): Board
    {
        return $this->buildBoard(
            "        @2" .
            "  @1      " .
            "          " .
            "          " .
            "@3      @4",
            5,
            $heroes
        );
    }

    public function buildM1Board(Heroes $heroes): Board
    {
        return $this->buildBoard(
            "##@1    ####    @4##" .
            "      ########      " .
            "        ####        " .
            "    []        []    " .
            "$-    ##    ##    $-" .
            "$-    ##    ##    $-" .
            "    []        []    " .
            "        ####  @3    " .
            "      ########      " .
            "##@2    ####      ##",
            10,
            $heroes
        );
    }

    public function getHeroes()
    {
        return new Heroes([
            new PlayerHero(new BaseHero(1, 'HeroOne', 100, 0, 0, new Position(1, 1))),
            new EnemyHero(new BaseHero(2, 'HeroTwo', 100, 0, 0, new Position(4, 0))),
            new EnemyHero(new BaseHero(3, 'HeroThree', 100, 0, 0, new Position(0, 4))),
            new EnemyHero(new BaseHero(4, 'HeroFour', 100, 0, 0, new Position(4, 4))),
        ]);
    }

    private function buildBoard(string $boardString, int $size, Heroes $heroes): Board
    {
        return new Board(new TileFactory($heroes), $size, $boardString);
    }
}
