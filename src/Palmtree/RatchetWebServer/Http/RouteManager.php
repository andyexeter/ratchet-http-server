<?php

namespace Palmtree\RatchetWebServer\Http;

use Monolog\Logger;
use Palmtree\RatchetWebServer\App;
use Palmtree\Service\Config\Config;
use Palmtree\Service\Config\ConfigParser;

class RouteManager
{
    protected $app;
    protected $config;
    protected $logger;

    public function __construct(App $app, Config $config, Logger $logger)
    {
        $this->app    = $app;
        $this->config = $config;
        $this->logger = $logger;
    }

    protected function addRoutes($routes)
    {
        foreach ($routes as $routeName => $route) {
            if (array_key_exists('controller', $route)) {
                $controller = $route['controller'];
            } else {
                $controller = $this->config->get('controllers') . '\\' . ucwords($routeName) . 'Controller';
            }

            $this->app->route($route['path'], new $controller($this->config, $this->logger), ['*']);
        }

        return true;
    }

    public function parseRouteConfig($path)
    {
        $parser = new ConfigParser($path);

        return $this->addRoutes($parser->getParameters());
    }

}
