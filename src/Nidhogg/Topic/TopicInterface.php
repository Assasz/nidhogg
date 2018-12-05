<?php

namespace Nidhogg\Topic;

/**
 * Interface TopicInterface
 *
 * @package Nidhogg\Topic
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
interface TopicInterface
{
    /**
     * Returns allowed origins for topic
     *
     * @return array
     */
    public function getAllowedOrigins(): array;

    /**
     * Returns topic host
     *
     * @return string?
     */
    public function getHost(): ?string;
}