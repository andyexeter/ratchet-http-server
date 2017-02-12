<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Palmtree\RatchetWebServer\App;
use Palmtree\RatchetWebServer\Controller\NotFoundController;
use Palmtree\RatchetWebServer\Http\RouteManager;
use Palmtree\Service\Config\Config;
use Palmtree\Service\Config\ConfigParser;

require dirname(__DIR__) . '/vendor/autoload.php';

// Config
$config = new Config([
    'paths' => ['root' => realpath(dirname(__DIR__))],
]);

$configParser = new ConfigParser($config->get('paths.root') . '/app/config/config.yml', $config->all());
$config->merge($configParser->getParameters());

// Logging
$logger = new Logger('access');
$logger->pushHandler(new StreamHandler($config->get('paths.log') . '/access.log', Logger::DEBUG));

// Log messages to console
$logger->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));

// App
$app = new App(
    $config->get('server.host'),
    $config->get('server.port'),
    $config->get('server.address'),
    new NotFoundController($config, $logger)
);

// Routes
$routeManager = new RouteManager($app, $config, $logger);
$routeManager->parseRouteConfig($config->get('paths.app') . '/config/routes.yml');

$url = "http://{$config->get('server.host')}:{$config->get('server.port')}";
echo "Waiting for connections on $url...\n";

$app->run();
