<?php
/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace GrizzIt\Configuration\Dao;

use GrizzIt\Configuration\Common\LocatorInterface;

class Locator implements LocatorInterface
{
    /**
     * Contains the key used in the registry to register the configuration.
     *
     * @var string
     */
    private $key;

    /**
     * Contains the location which the compiler will seek to find files.
     *
     * @var string
     */
    private $location;

    /**
     * Constructor
     *
     * @param string $key
     * @param string $location
     */
    public function __construct(
        string $key,
        string $location
    ) {
        $this->key = $key;
        $this->location = $location;
    }

    /**
     * Retrieves the location in which configuration needs to be fetched.
     *
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * Retrieves the key for configuration.
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }
}
