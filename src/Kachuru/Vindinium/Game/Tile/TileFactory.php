<?php

namespace Kachuru\Vindinium\Game\Tile;

use Kachuru\Vindinium\Game\Hero\Heroes;
use Kachuru\Vindinium\Game\Hero\PlayerHero;
use Kachuru\Vindinium\Game\Position;

class TileFactory
{
    const CLASS_NAMESPACE = 'Kachuru\Vindinium\Game\Tile\\';
    const TILE_TYPES = [
        'EmptyTile', 'WallTile', 'MineTile', 'TavernTile', 'HeroTile'
    ];

    private $heroes;

    public function __construct(Heroes $heroes)
    {
        $this->heroes = $heroes;
    }

    public function buildTile(string $content, Position $position): BoardTile
    {
        $tile = array_reduce(
            self::TILE_TYPES,
            function ($tile, $tileType) use ($content, $position) {
                if (is_null($tile)) {
                    if (preg_match((self::CLASS_NAMESPACE.$tileType)::TILE_SPECIFICATION, $content, $match)) {
                        $tile = $this->doBuildTile($position, $tileType, $match);
                    }
                }

                return $tile;
            }
        );

        if (is_null($tile)) {
            throw new \RuntimeException(
                sprintf('Could not find matching type for tile content "%s"', $content)
            );
        }

        return $tile;
    }

    private function doBuildTile($position, $tileType, $match)
    {
        $hero = (array_key_exists('hero', $match) && is_numeric($match['hero']))
            ? $this->heroes->getHero($match['hero'])
            : null;

        if ($tileType == 'HeroTile') {
            $tileType = ($hero instanceof PlayerHero)
                ? 'PlayerHeroTile'
                : 'EnemyHeroTile';
        }

        $class = self::CLASS_NAMESPACE.$tileType;
        return new BoardTile($position, new $class(), $hero);
    }
}
