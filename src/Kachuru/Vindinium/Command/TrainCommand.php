<?php

namespace Kachuru\Vindinium\Command;

use Kachuru\Base\Command\Command;
use Kachuru\Vindinium\Bot\BasicBot;
use Kachuru\Vindinium\Bot\CleverBot;
use Kachuru\Vindinium\Client;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TrainCommand extends Command
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('train');
        $this->setDescription('Run the bot in training mode');
        $this->addOption('bot', 'b', InputOption::VALUE_OPTIONAL, 'Bot scheme to use', 'clever');
        $this->addOption('turns', 't', InputOption::VALUE_OPTIONAL, 'Number of turns to run for', 300);
        $this->addOption('map', 'm', InputOption::VALUE_OPTIONAL, 'Map to run');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bot = $input->getOption('bot') == 'basic'
            ? new BasicBot()
            : new CleverBot();

        $this->client->startTraining($bot, $input->getOption('turns'), $input->getOption('map'));
    }
}
