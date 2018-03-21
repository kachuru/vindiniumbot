<?php

namespace Kachuru\Vindinium\Game;

use Kachuru\Vindinium\Bot\BasicBot;
use Kachuru\Vindinium\Game\Tile\TileFactory;

class Client
{
    CONST TIMEOUT = 15;

    private $key;
    private $server;
    private $endPoint;
    private $params = [];

    public function __construct($server, $key)
    {
        $this->server = $server;
        $this->key = $key;
    }

    public function startTraining($turns = 300, $map = 1)
    {
        $this->endPoint = '/api/training';
        $this->params = [
            'key' => $this->key,
            'turns' => $turns,
            'map' => 'm' . $map,
        ];

        echo "Starting training mode" . PHP_EOL;

        $this->run();

        echo "Finished" . PHP_EOL;
    }

    public function startArena($games = 1)
    {
        $this->endPoint = '/api/arena';
        $this->params = [
            'key' => $this->key
        ];

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
        $tileFactory = new TileFactory();

        // Get the initial state
        $state = $this->getNewGameState();

        $game = Game::buildFromResponse($state, $tileFactory);

        printf('Playing at: %s as player #%d' . PHP_EOL . PHP_EOL, $state['viewUrl'], $game->getPlayer()->getId());

        $windowSize = $state['game']['board']['size'] + 3;

        for ($i = 0; $i < $windowSize; $i++) {
            echo PHP_EOL;
        }

        ob_start();
        while (!$game->isFinished()) {
            $bot = new BasicBot($game->getPlayer());

            $this->cursorUp($windowSize);

            $board = $game->getBoard();
            print($board . PHP_EOL);

            $player = $game->getPlayer();
            print($player->print() . PHP_EOL);

            // Move to some direction
            $url = $state['playUrl'];
            $direction = $bot->move($board, $game->getPlayer()->getPosition());

            $state = $this->move($url, $direction);

            printf("Turn %4d - Move: %5s" . PHP_EOL, $state['game']['turn'] / 4, $direction);

            $game = Game::buildFromResponse($state, $tileFactory);
            ob_flush();
        }
        echo PHP_EOL . PHP_EOL;
        ob_end_clean();
    }

    private function getNewGameState()
    {
        // Wait for 10 minutes
        $response = HttpPost::post($this->server . $this->endPoint, $this->params, 10 * 60);

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
            $response = HttpPost::post($url, array('dir' => $direction), self::TIMEOUT);
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

    function cursorUp($n = 1)
    {
        $this->moveCursor($n, 'A');
    }

    function cursorDown($n = 1)
    {
        $this->moveCursor($n, 'B');
    }

    function cursorRight($n = 1)
    {
        $this->moveCursor($n, 'C');
    }

    function cursorLeft($n = 1)
    {
        $this->moveCursor($n, 'D');
    }

    function moveCursor($n, $d)
    {
        echo "\033[".$n.$d;
    }
}
