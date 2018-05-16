<?php

namespace Kachuru\Vindinium\Game\Hero;

use Kachuru\Vindinium\Game\Position;

interface Hero
{
    public static function buildFromVindiniumResponse(array $response);
    public function getId(): int;
    public function getName(): string;
    public function getLife(): int;
    public function getGold(): int;
    public function getMineCount(): int;
    public function getPosition(): Position;
}
