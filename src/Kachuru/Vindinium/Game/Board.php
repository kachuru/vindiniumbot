<?php

namespace Kachuru\Vindinium\Game;

use Kachuru\Vindinium\Game\Tile\BoardTile;
use Kachuru\Vindinium\Game\Tile\TileFactory;

class Board
{
    const REQUIRE_KEYS = ['size', 'tiles'];

    private $size;
    private $tiles;
    private $tileTypes;

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
            function ($row, $y) use ($tileFactory) {
                return array_map(
                    function($tileString, $x) use ($tileFactory, $y) {
                        return $this->buildTile($tileFactory, $tileString, $x, $y);
                    },
                    str_split($row, 2),
                    range(0, $this->size - 1)
                );
            },
            str_split($tiles, $this->size * 2),
            range(0, $this->size - 1)
        );
    }

    public function getBoardTiles(): array
    {
        return $this->tiles;
    }

    public function getSize(): int
    {
        return (int) $this->size;
    }

    public function getMineTiles()
    {
        return $this->tileTypes['MineTile'];
    }

    public function getTavernTiles()
    {
        return $this->tileTypes['TavernTile'];
    }

    public function getBoardTileAtPosition(Position $position): ?BoardTile
    {
        if (array_key_exists($position->getY(), $this->tiles)
            && array_key_exists($position->getX(), $this->tiles[$position->getY()])) {
            return $this->tiles[$position->getY()][$position->getX()];
        }

        return null;
    }

    /**
     * FIXME: This should take a BoardTile instead of a position
     */
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

    private function buildTile(TileFactory $tileFactory, $tileString, $x, $y)
    {
        $tile = $tileFactory->buildTile($tileString, new Position($x, $y));
        $this->tileTypes[$tile->getTypeName()][] = $tile;
        return $tile;
    }
}
