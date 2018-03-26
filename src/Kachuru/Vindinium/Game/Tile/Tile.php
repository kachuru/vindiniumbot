<?php

namespace Kachuru\Vindinium\Game\Tile;

class Tile
{
    private $type;
    private $content;
    private $player = 0;

    public function __construct(TileType $type, string $content, $player = null)
    {
        $this->type = $type;
        $this->content = $content;

        if (!is_null($player) && $player != '-') {
            $this->player = (int) $player;
        }
    }

    public function __toString(): string
    {
        return $this->content;
    }

    public function getType(): TileType
    {
        return $this->type;
    }

    public function getPlayer(): int
    {
        return (int) $this->player;
    }

    public function getTypeName(): string
    {
        $class = get_class($this->type);
        return substr($class, strrpos($class, '\\') + 1);
    }

    public function isWalkable(): bool
    {
        return ($this->type)::WALKABLE;
    }

    public function getBaseMoveCost(): int
    {
        return ($this->type)::BASE_MOVE_COST;
    }
}
