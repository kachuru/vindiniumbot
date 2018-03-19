<?php

use Kachuru\Vindinium\Bot\BasicBot;
use Kachuru\Vindinium\Bot\Bot;
use Kachuru\Vindinium\Game\Game;
use Kachuru\Vindinium\Game\Tile\TileFactory;

class Client
{
    CONST TIMEOUT = 15;

    private $key;
    private $mode;
    private $numberOfGames;
    private $numberOfTurns;
    private $serverUrl = 'http://vindinium.org';

    public function __construct()
    {
        if ($_SERVER['argc'] < 4) {
            echo "Usage: " . $_SERVER['SCRIPT_FILENAME'] . " <key> <[training|arena]> <number-of-games|number-of-turns> [server-url]\n";
            echo "Example: " . $_SERVER['SCRIPT_FILENAME'] . " mySecretKey training 20\n";
        } else {
            $this->key = $_SERVER['argv'][1];
            $this->mode = $_SERVER['argv'][2];

            if ($this->mode == "training") {
                $this->numberOfGames = 1;
                $this->numberOfTurns = (int)$_SERVER['argv'][3];
            } else {
                $this->numberOfGames = (int)$_SERVER['argv'][3];
                $this->numberOfTurns = 300; # Ignored in arena mode
            }

            if ($_SERVER['argc'] == 5) {
                $this->serverUrl = $_SERVER['argv'][4];
            }
        }
    }

    public function load()
    {
        require('./HttpPost.php');

        for ($i = 0; $i <= ($this->numberOfGames - 1); $i++) {
            $this->start();
            echo "\nGame finished: " . ($i + 1) . "/" . $this->numberOfGames . "\n";
        }
    }

    private function start()
    {
        $tileFactory = new TileFactory();

        // Starts a game with all the required parameters
        if ($this->mode == 'arena') {
            echo "Connected and waiting for other players to join...\n";
        }

        // Get the initial state
        $state = $this->getNewGameState();

        $game = Game::buildFromResponse($state, $tileFactory);

        printf('Playing at: %s as player #%d' . PHP_EOL . PHP_EOL, $state['viewUrl'], $game->getPlayer()->getId());

        $bot = new BasicBot($game->getPlayer());

        $windowSize = $state['game']['board']['size'] + 2;

        for ($i = 0; $i < $windowSize; $i++) {
            echo PHP_EOL;
        }

        ob_start();
        while (!$game->isFinished()) {

            $this->cursorUp($windowSize);

            $board = $game->getBoard();
            print($board . PHP_EOL);

            $player = $game->getPlayer();
            print($player->print() . PHP_EOL);
            ob_flush();

            // Move to some direction
            $url = $state['playUrl'];
            $direction = $bot->move($board);
            $state = $this->move($url, $direction);

            $game = Game::buildFromResponse($state, $tileFactory);
        }
        ob_end_clean();
    }

    private function getNewGameState()
    {
        $apiEndpoint = '/api/arena';
        $params = [
            'key' => $this->key
        ];

        // Get a JSON from the server containing the current state of the game
        if ($this->mode == 'training') {
            $apiEndpoint = '/api/training';
            $params['turns'] = $this->numberOfTurns;
            $params['map'] = 'm1';
        }

        // Wait for 10 minutes
        $response = HttpPost::post($this->serverUrl . $apiEndpoint, $params, 10 * 60);

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
