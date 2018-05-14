<?php

namespace Kachuru\Vindinium\Command;

use Kachuru\Base\Command\Command;
use Kachuru\Vindinium\Bot\BotFactory;
use Kachuru\Vindinium\Display\DisplayFactory;
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
    /**
     * @var DisplayFactory
     */
    private $displayFactory;

    public function __construct(VindiniumClient $client, BotFactory $botFactory, DisplayFactory $displayFactory)
    {
        $this->client = $client;
        $this->botFactory = $botFactory;
        $this->displayFactory = $displayFactory;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('train');
        $this->setDescription('Run the bot in training mode');
        $this->addOption('turns', 't', InputOption::VALUE_OPTIONAL, 'Number of turns to run for', 300);
        $this->addOption('map', 'm', InputOption::VALUE_OPTIONAL, 'Map to run');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->client->startTraining(
            $this->botFactory->getBotByHandle($input->getOption('bot')),
            $this->displayFactory->getDisplay($input->getOption('display')),
            $input->getOption('turns'), $input->getOption('map')
        );
    }
}
