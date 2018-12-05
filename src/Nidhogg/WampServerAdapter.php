<?php

namespace Nidhogg;

use Yggdrasil\Core\Configuration\ConfigurationInterface;
use Nidhogg\Routing\RouteCollector;

/**
 * Class WampServerAdapter
 *
 * Adapter for WampServer
 *
 * @package Nidhogg
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
final class WampServerAdapter
{
    /**
     * Instance of WampServer
     *
     * @var WampServer
     */
    private $server;

    /**
     * Instance of RouteCollector
     *
     * @var RouteCollector
     */
    private $routeCollector;

    /**
     * Application configuration
     *
     * @var ConfigurationInterface
     */
    private $appConfiguration;

    /**
     * WampServerAdapter constructor.
     *
     * @param WampServer $server
     * @param RouteCollector $routeCollector
     * @param ConfigurationInterface $appConfiguration
     */
    public function __construct(WampServer $server, RouteCollector $routeCollector, ConfigurationInterface $appConfiguration)
    {
        $this->server = $server;
        $this->routeCollector = $routeCollector;
        $this->appConfiguration = $appConfiguration;
    }

    /**
     * Runs configured WampServer
     *
     * @throws \Exception
     */
    public function runServer(): void
    {
        $routes = $this->routeCollector
            ->setConfiguration($this->appConfiguration)
            ->getRouteCollection();

        $this->server
            ->setConfiguration($this->appConfiguration)
            ->setRoutes($routes)
            ->run();
    }
}