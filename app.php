<?php

require_once 'vendor/autoload.php';

include('config.php');

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;

use BotMan\BotMan\Cache\SymfonyCache;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

use App\Conversations\OnboardingConversation;
use App\Providers\Apifootball;

// Load the driver(s) you want to use
DriverManager::loadDriver(\BotMan\Drivers\Facebook\FacebookDriver::class);

// Create an instance
$adapter = new FilesystemAdapter();
$botman = BotManFactory::create($config, new SymfonyCache($adapter));

$provider = new Apifootball($config['app']['apifootball_token']);

// Give the bot something to listen for.
$botman->hears('Hello', function($bot) use($provider) {
    $bot->startConversation(new OnboardingConversation($provider));
});

$botman->fallback(function($bot) {
    $bot->reply('Sorry, I did not understand these commands. Here is a list of commands I understand: ...');
});

// Start listening
$botman->listen();
