<?php

namespace Nidhogg\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Yggdrasil\Core\Configuration\ConfigurationInterface;

/**
 * Class WampServerCommand
 *
 * Runs WAMP server
 *
 * @package Yggdrasil\Component\Nidhogg\Command
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class WampServerCommand extends Command
{
    /**
     * Application configuration
     *
     * @var ConfigurationInterface
     */
    private $appConfiguration;

    /**
     * WampServerCommand constructor.
     *
     * @param ConfigurationInterface $appConfiguration
     */
    public function __construct(ConfigurationInterface $appConfiguration)
    {
        $this->appConfiguration = $appConfiguration;

        parent::__construct();
    }

    /**
     * Configures command
     */
    protected function configure(): void
    {
        $this
            ->setName('nidhogg:wamp-server:run')
            ->setDescription('Runs WAMP server.')
            ->setHelp('This command will start WAMP server.');
    }

    /**
     * Executes command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $configuration = $this->appConfiguration->getConfiguration();

        $output->writeln([
            "WAMP server is running!",
            "On {$configuration['wamp_server']['host']}:{$configuration['wamp_server']['port']}"
        ]);

        $this->appConfiguration->loadDriver('wampServer')->runServer();
    }
}