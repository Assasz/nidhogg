<?php

namespace Nidhogg\Driver;

use Nidhogg\Routing\RouteCollector;
use Nidhogg\WampServer;
use Nidhogg\WampServerFacade;
use Yggdrasil\Core\Configuration\ConfigurationInterface;
use Yggdrasil\Core\Driver\DriverInterface;
use Yggdrasil\Core\Exception\MissingConfigurationException;
use Yggdrasil\Core\Exception\DriverNotFoundException;

/**
 * Class WampServerDriver
 *
 * [Nidhogg] WAMP server driver
 *
 * @package Nidhogg\Driver
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class WampServerDriver implements DriverInterface
{
    /**
     * Instance of driver
     *
     * @var DriverInterface
     */
    private static $driverInstance;

    /**
     * Instance of server facade
     *
     * @var WampServerFacade
     */
    private static $serverFacadeInstance;

    /**
     * Prevents object creation and cloning
     */
    private function __construct() {}

    private function __clone() {}

    /**
     * Installs server adapter driver
     *
     * @param ConfigurationInterface $appConfiguration
     * @return DriverInterface
     *
     * @throws MissingConfigurationException if host, port or topic_namespace is not configured
     */
    public static function install(ConfigurationInterface $appConfiguration): DriverInterface
    {
        if (self::$driverInstance === null) {
            $requiredConfig = ['host', 'port', 'topic_namespace'];

            if (!$appConfiguration->isConfigured($requiredConfig, 'wamp_server')) {
                throw new MissingConfigurationException($requiredConfig, 'wamp_server');
            }

            self::$serverFacadeInstance = new WampServerFacade(
                new WampServer(), new RouteCollector(), $appConfiguration
            );

            self::$driverInstance = new WampServerDriver();
        }

        return self::$driverInstance;
    }

    /**
     * Runs WAMP server
     *
     * @throws \Exception
     * @throws \ReflectionException
     * @throws DriverNotFoundException
     */
    public function runServer()
    {
        self::$serverFacadeInstance->runServer();
    }
}