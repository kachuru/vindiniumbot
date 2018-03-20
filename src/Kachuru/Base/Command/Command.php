<?php

namespace Kachuru\Base\Command;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Kachuru\Base\CommandConfigurator\CommandConfigurator;

abstract class Command extends SymfonyCommand
{
    public function addCommandConfigurator(CommandConfigurator $commandConfigurator)
    {
        $commandConfigurator->configure($this);
    }
}
