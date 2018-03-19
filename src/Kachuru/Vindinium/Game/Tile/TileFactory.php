<?php

namespace Kachuru\Vindinium\Game\Tile;

class TileFactory
{
    const TILE_TYPES = [
        'EmptyTile', 'WallTile', 'MineTile', 'TavernTile', 'PlayerTile'
    ];

    public function buildTile(string $content): Tile
    {
        return $this->getTileFromContent($content);
    }

    private function getTileFromContent($content)
    {
        $type = array_reduce(
            self::TILE_TYPES,
            function ($tile, $tileType) use ($content) {
                /**
                 * @var \Kachuru\Vindinium\Game\Tile\TileType $class
                 */
                $class = 'Kachuru\Vindinium\Game\Tile\\' . $tileType;
                if (preg_match($class::getPattern(), $content, $match)) {
                    $tile = new Tile(
                        new $class(),
                        $content,
                        array_key_exists('player', $match) ? $match['player'] : null
                    );
                }

                return $tile;
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
