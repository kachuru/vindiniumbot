<?php

namespace Kachuru\Vindinium;

use Kachuru\Base\CommandConfigurator\CommandConfigurator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;

class VindiniumCommandConfigurator implements CommandConfigurator
{
    public function configure(Command $command)
    {
        $command->addOption('bot', 'b', InputOption::VALUE_OPTIONAL, 'Bot scheme to use', 'random');
        $command->addOption('display', 'd', InputOption::VALUE_OPTIONAL, 'Display mode to use', 'standard');
    }
}
