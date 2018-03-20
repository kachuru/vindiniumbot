#!/usr/bin/env php
<?php

use Kachuru\Base\DependencyInjection\ContainerSetUp;

foreach (['../../../autoload.php', '../vendor/autoload.php', 'vendor/autoload.php'] as $file) {
    if (file_exists(__DIR__ . '/' . $file)) {
        require __DIR__ . '/' . $file;
        unset($file);
        break;
    }
}

ContainerSetUp::bootstrapContainer()->get('vindinium-bot.console')->run();
