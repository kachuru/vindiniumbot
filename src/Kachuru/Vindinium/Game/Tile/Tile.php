<?php

namespace Kachuru\Vindinium\Game\Tile;

class Tile
{
    private $type;
    private $content;

    public function __construct(TileType $type, string $content)
    {
        $this->type = $type;
        $this->content = $content;
    }

    public function getType(): TileType
    {
        return $this->type;
    }

    public function isWalkable(): bool
    {
        return $this->type->isWalkable();
    }

    public function __toString(): string
    {
        return $this->content;
    }
}
