<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Palmtree\RatchetWebServer\Controller\AboutController;
use Palmtree\RatchetWebServer\Controller\HomeController;
use Palmtree\Service\Config;
use Ratchet\App;

require dirname( __DIR__ ) . '/vendor/autoload.php';

// Config
$configArray = include dirname( __DIR__ ) . '/app/config.php';
$config      = new Config( $configArray );

// Logging
$logger = new Logger( 'access' );
$logger->pushHandler( new StreamHandler( $config->get( 'log_dir' ) . '/access.log', Logger::DEBUG ) );
$logger->pushHandler( new StreamHandler( 'php://stdout', Logger::DEBUG ) );

$serverConfig = $config->get( 'server' );

$app = new App( $serverConfig['host'], $serverConfig['port'], $serverConfig['address'] );

$app->route( '/', new HomeController( $config, $logger ), [ '*' ] );
$app->route( '/about', new AboutController( $config, $logger ), [ '*' ] );

$app->run();
