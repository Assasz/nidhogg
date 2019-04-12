<?php

namespace Nidhogg;

use Yggdrasil\Core\Configuration\ConfigurationInterface;
use Yggdrasil\Core\Exception\DriverNotFoundException;
use Nidhogg\Routing\RouteCollector;

/**
 * Class WampServerFacade
 *
 * Decouples WampServer from its dependencies
 *
 * @package Nidhogg
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
final class WampServerFacade
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
     * WampServerFacade constructor.
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
     * @throws \ReflectionException
     * @throws DriverNotFoundException
     */
    public function runServer(): void
    {
        $this->server
            ->setConfiguration($this->appConfiguration)
            ->setRoutes($this->routeCollector
                ->setConfiguration($this->appConfiguration)
                ->collectRoutes())
            ->run();
    }
}
