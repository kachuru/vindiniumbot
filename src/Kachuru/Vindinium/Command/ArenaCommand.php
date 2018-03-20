<?php

namespace Kachuru\Vindinium\Command;

use Kachuru\Base\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ArenaCommand extends Command
{
    protected function configure()
    {
        $this->setName('arena');
        $this->setDescription('Run the bot in arena mode');
        $this->addOption('games', 'g');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Your test worked!');
    }
}
