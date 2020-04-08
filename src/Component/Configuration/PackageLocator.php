<?php
/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace GrizzIt\Configuration\Component\Configuration;

class PackageLocator
{
    /**
     * Contains the locations used in the project.
     */
    private static $locations = [];

    /**
     * Register a location of package.
     *
     * @param string $location
     *
     * @return void
     */
    public static function registerLocation(string $location): void
    {
        static::$locations[] = $location;
    }

    /**
     * Retrieves the registered locations.
     *
     * @return array
     */
    public static function getLocations(): array
    {
        return static::$locations;
    }
}
