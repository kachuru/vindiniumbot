<?php

namespace Kachuru\Vindinium\Game\Tile;

use Kachuru\Vindinium\Game\Position;

class TileFactory
{
    const TILE_TYPES = [
        'EmptyTile', 'WallTile', 'MineTile', 'TavernTile', 'PlayerTile'
    ];

    public function buildTile(Position $position, string $content): Tile
    {
        return new Tile(
            $this->getTileTypeFromContent($content), $position, $content
        );
    }

    private function getTileTypeFromContent($content)
    {
        $type = array_reduce(
            self::TILE_TYPES,
            function ($carry, $tileType) use ($content) {
                $class = 'Kachuru\Vindinium\Game\Tile\\' . $tileType;
                if (preg_match($class::getPattern(), $content)) {
                    $carry = new $class();
                }
                return $carry;
            }
        );

        if (is_null($type)) {
            throw new \RuntimeException(
                sprintf('Could not find matching type for tile content "%s"', $content)
            );
        }

        return $type;
    }
}
