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

class ArenaCommand extends Command
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
        $this->setName('arena');
        $this->setDescription('Run the bot in arena mode');
        $this->addOption('games', 'g', InputOption::VALUE_OPTIONAL, 'Number of games to run for', 1);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bot = $this->botFactory->getBotByHandle($input->getOption('bot'));

        $this->client->startArena($bot, $input->getOption('games'));
    }
}
