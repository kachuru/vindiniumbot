<?php

namespace Kachuru\Vindinium\Display;

class StandardDisplay implements Display
{
    public function getHandle(): string
    {
        return 'standard';
    }
}
