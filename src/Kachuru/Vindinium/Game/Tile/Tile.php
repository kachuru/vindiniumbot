<?php

namespace Kachuru\Vindinium\Game\Tile;

use Kachuru\Vindinium\Game\Position;

class Tile
{
    /**
     * @var TileType
     */
    private $type;
    /**
     * @var Position
     */
    private $position;
    /**
     * @var string
     */
    private $content;

    public function __construct(TileType $type, Position $position, string $content)
    {
        $this->type = $type;
        $this->position = $position;
        $this->content = $content;
    }

    public function getType(): TileType
    {
        return $this->type;
    }

    public function getPosition(): Position
    {
        return $this->position;
    }

    public function __toString(): string
    {
        return $this->content;
    }
}
