<?php

namespace Kachuru\Vindinium\Command;

use Kachuru\Base\Command\Command;
use Kachuru\Vindinium\Game\Client;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ArenaCommand extends Command
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('arena');
        $this->setDescription('Run the bot in arena mode');
        $this->addOption('games', 'g', InputOption::VALUE_OPTIONAL, 'Number of games to run for', 1);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->client->startArena($input->getOption('games'));
    }
}
