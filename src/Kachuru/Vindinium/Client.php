<?php

namespace Kachuru\Vindinium;

use Kachuru\Util\ConsoleOutput;
use Kachuru\Util\ConsoleWindow;
use Kachuru\Vindinium\Bot\Bot;
use Kachuru\Vindinium\Game\Game;

class Client
{
    const GAME_WAIT_TIMEOUT = 15;
    const NEW_GAME_WAIT_TIMEOUT = 30 * 60;

    private $key;
    private $server;
    private $endPoint;
    private $params = [];
    /**
     * @var Bot
     */
    private $bot;

    public function __construct($server, $key)
    {
        $this->server = $server;
        $this->key = $key;
    }

    public function startTraining(Bot $bot, $turns = 300, $map = null)
    {
        $this->endPoint = '/api/training';
        $this->params = [
            'key' => $this->key,
            'turns' => $turns,
        ];

        if (!is_null($map)) {
            $this->params['map'] = 'm' . $map;
        }

        $this->bot = $bot;

        echo "Starting training mode" . PHP_EOL;

        $this->run();

        echo "Finished" . PHP_EOL;
    }

    public function startArena(Bot $bot, $games = 1)
    {
        $this->endPoint = '/api/arena';
        $this->params = [
            'key' => $this->key
        ];

        $this->bot = $bot;

        echo "Starting arena mode" . PHP_EOL;

        for ($i = 1; $i <= $games; $i++) {
            echo "Game starting: {$i}/{$games}" . PHP_EOL;
            echo "Connected and waiting for other players to join..." . PHP_EOL;

            $this->run();

            echo "Game finished: {$i}/{$games}" . PHP_EOL;
        }
    }

    private function run()
    {
        // Get the initial state
        $state = $this->getNewGameState();

        $game = Game::buildFromVindiniumResponse($state);

        $boardSize = $state['game']['board']['size'];

        $consoleOutput = (new ConsoleOutput(
            [
                new ConsoleWindow(1, 1, 118, 1),
                new ConsoleWindow(1, 3, $boardSize * 2, $boardSize),
                new ConsoleWindow(($boardSize * 2) + 2, 3, 117 - ($boardSize * 2), $boardSize)
            ]
        ))->prepare();

        // ob_start();
        while (!$game->isFinished()) {
            $consoleOutput->write(
                0,
                sprintf(
                    ' Game: %s - Turn: %d    Bot: %s',
                    $state['viewUrl'],
                    $state['game']['turn'],
                    $this->bot->getHandle()
                )
            );

            $board = $game->getBoard();
            $consoleOutput->write(1, $board);

            // Move to some direction
            $url = $state['playUrl'];

            $turnStart = microtime(true);
            $direction = $this->bot->chooseNextMove($board, $game->getHero());
            $decisionTime = microtime(true) - $turnStart;

            $state = $this->move($url, $direction);

            $lines = [];
            foreach ($game->getHeroes() as $hero) {
                $lines[] = " " . $hero;
                if ($hero->getId() == $game->getHero()->getId()) {
                    $lines[] = sprintf("    - Move: %5s in %.3fms    ", $direction, $decisionTime * 1000) . PHP_EOL;
                }
            }

            $consoleOutput->write(2, implode(PHP_EOL, $lines));

            $game = Game::buildFromVindiniumResponse($state);
            // ob_flush();
        }
        // ob_end_clean();
    }

    private function getNewGameState()
    {
        $response = HttpPost::post($this->server . $this->endPoint, $this->params, self::NEW_GAME_WAIT_TIMEOUT);

        if (isset($response['headers']['status_code']) && $response['headers']['status_code'] == 200) {
            return json_decode($response['content'], true);
        } else {
            echo "Error when creating the game\n";
            echo $response['content'];
        }
    }

    private function move($url, $direction)
    {
        /*
         * Send a move to the server
         * Moves can be one of: 'Stay', 'North', 'South', 'East', 'West'
         */

        try {
            $response = HttpPost::post($url, array('dir' => $direction), self::GAME_WAIT_TIMEOUT);
            if (isset($response['headers']['status_code']) && $response['headers']['status_code'] == 200) {
                return json_decode($response['content'], true);
            } else {
                echo "Error HTTP " . $response['headers']['status_code'] . "\n" . $response['content'] . "\n";
                return array('game' => array('finished' => true));
            }
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
            return array('game' => array('finished' => true));
        }
    }
}
