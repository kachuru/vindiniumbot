<?php

namespace Kachuru\Vindinium\Command;

use Kachuru\Base\Command\Command;
use Kachuru\Vindinium\Bot\BasicBot;
use Kachuru\Vindinium\Bot\BotFactory;
use Kachuru\Vindinium\Bot\CleverBot;
use Kachuru\Vindinium\VindiniumClient;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TrainCommand extends Command
{
    private $client;
    /**
     * @var BotFactory
     */
    private $botFactory;

    public function __construct(VindiniumClient $client, BotFactory $botFactory)
    {
        $this->client = $client;
        $this->botFactory = $botFactory;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('train');
        $this->setDescription('Run the bot in training mode');
        $this->addOption('bot', 'b', InputOption::VALUE_OPTIONAL, 'Bot scheme to use', 'random');
        $this->addOption('turns', 't', InputOption::VALUE_OPTIONAL, 'Number of turns to run for', 300);
        $this->addOption('map', 'm', InputOption::VALUE_OPTIONAL, 'Map to run');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bot = $this->botFactory->getBotByHandle($input->getOption('bot'));

        $this->client->startTraining($bot, $input->getOption('turns'), $input->getOption('map'));
    }
}
