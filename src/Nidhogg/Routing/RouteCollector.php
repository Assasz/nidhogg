<?php

namespace Nidhogg\Routing;

use HaydenPierce\ClassFinder\ClassFinder;
use Yggdrasil\Core\Configuration\ConfigurationInterface;
use Nidhogg\Exception\NotTopicFoundException;
use Nidhogg\Topic\TopicInterface;

/**
 * Class RouteCollector
 *
 * Collects topics routes
 *
 * @package Nidhogg\Routing
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
final class RouteCollector
{
    /**
     * Application Configuration
     *
     * @var ConfigurationInterface
     */
    private $appConfiguration;

    /**
     * Sets application configuration
     *
     * @param ConfigurationInterface $appConfiguration
     * @return RouteCollector
     */
    public function setConfiguration(ConfigurationInterface $appConfiguration): RouteCollector
    {
        $this->appConfiguration = $appConfiguration;

        return $this;
    }

    /**
     * Returns routes collected from topics existing in application
     *
     * @return array
     * @example route: /chat/member => ChatMemberTopic
     *
     * @throws \Exception
     * @throws \ReflectionException
     * @throws NotTopicFoundException if found object is not a topic instance
     */
    public function collectRoutes(): array
    {
        $configuration = $this->appConfiguration->getConfiguration();
        $topics = ClassFinder::getClassesInNamespace(rtrim($configuration['wamp_server']['topic_namespace'], '\\'));

        foreach ($topics as $topic) {
            $topicReflection = new \ReflectionClass($topic);
            $topicName       = $topicReflection->getName();
            $topicShortName  = $topicReflection->getShortName();
            $topicNameParts  = preg_split('/(?=[A-Z])/', $topicShortName);

            array_pop($topicNameParts);

            $topicPath       = mb_strtolower(implode('/', $topicNameParts));
            $topicInstance   = new $topicName($this->appConfiguration->loadDrivers());

            if (!$topicInstance instanceof TopicInterface) {
                throw new NotTopicFoundException($topicShortName . ' is not a topic instance.');
            }

            $route = (new Route())
                ->setPath($topicPath)
                ->setTopic($topicInstance);

            $routeCollection[] = $route;
        }

        return $routeCollection ?? [];
    }
}