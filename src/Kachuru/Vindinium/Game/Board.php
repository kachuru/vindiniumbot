<?php

namespace Kachuru\Vindinium\Game;

use Kachuru\Vindinium\Game\Tile\TileFactory;

class Board
{
    const REQUIRE_KEYS = ['board', 'size'];

    private $size;
    private $tiles;

    public static function buildFromVindiniumResponse(TileFactory $tileFactory, array $response): self
    {
        foreach (self::REQUIRE_KEYS as $key) {
            if (!array_key_exists($key, $response)) {
                throw new \InvalidArgumentException(
                    sprintf("Key '%s' not present in Vindinium response array", $key)
                );
            }
        }

        return new self($tileFactory, $response['size'], $response['tiles']);
    }

    public function __construct(TileFactory $tileFactory, int $size, string $tiles)
    {
        $this->size = $size;

        $this->tiles = array_map(
            function ($row) use ($tileFactory) {
                return array_map(function($tileString) use ($tileFactory) {
                    return $tileFactory->buildTile($tileString);
                },
                str_split($row, 2));
            },
            str_split($tiles, $this->size * 2)
        );
    }

    public function getBoardTileAtPosition(Position $position): ?BoardTile
    {
        if (array_key_exists($position->getY(), $this->tiles)
            && array_key_exists($position->getX(), $this->tiles[$position->getY()])) {
            return new BoardTile($this->tiles[$position->getY()][$position->getX()], $position);
        }

        return null;
    }

    public function getAdjacentBoardTiles(Position $position): array
    {
        $tiles = array_reduce(
            [[0, -1], [-1, 0], [+1, 0], [0, +1]],
            function ($positions, $adj) use ($position) {
                $boardTile = $this->getBoardTileAtPosition(
                    new Position($position->getX() + $adj[0], $position->getY() + $adj[1])
                );

                if ($boardTile instanceof BoardTile) {
                    $positions[] = $boardTile;
                }

                return $positions;
            }
        );

        return $tiles;
    }

    public function __toString(): string
    {
        return (string) implode(PHP_EOL, array_map(
            function ($row) {
                return implode('', $row);
            },
            $this->tiles
        )) . PHP_EOL;
    }
}
