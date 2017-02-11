<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Palmtree\RatchetWebServer\Controller\AboutController;
use Palmtree\RatchetWebServer\Controller\HomeController;
use Palmtree\RatchetWebServer\Controller\NotFoundController;
use Palmtree\RatchetWebServer\App;
use Palmtree\Service\Config\Config;
use Palmtree\Service\Config\ConfigParser;

require dirname( __DIR__ ) . '/vendor/autoload.php';

// Config
//$configArray = include dirname( __DIR__ ) . '/app/config.php';
$config       = new Config( [
	'paths' => [ 'root' => realpath( dirname( __DIR__ ) ) ],
] );
$configParser = new ConfigParser( __DIR__ . '/../app/config/config.yml', $config );
$config->merge( $configParser->getParameters() );

// Logging
$logger = new Logger( 'access' );
$logger->pushHandler( new StreamHandler( $config->get( 'log_dir' ) . '/access.log', Logger::DEBUG ) );
// Log messages to console
$logger->pushHandler( new StreamHandler( 'php://stdout', Logger::DEBUG ) );

// App
$serverConfig = $config->get( 'server' );

$app = new App(
	$serverConfig['host'],
	$serverConfig['port'],
	$serverConfig['address'],
	new NotFoundController( $config, $logger )
);

// Routes
$app->route( '/', new HomeController( $config, $logger ), [ '*' ] );
$app->route( '/about', new AboutController( $config, $logger ), [ '*' ] );

$url = "http://{$serverConfig['host']}:{$serverConfig['port']}";
echo "Waiting for connections on $url...\n";

$app->run();
