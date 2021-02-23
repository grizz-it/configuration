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
     *
     * @var array
     */
    private static array $locations = [];

    /**
     * Register a location of package.
     *
     * @param string $location
     * @param string $name
     * @param string[] $sequence
     *
     * @return void
     */
    public static function registerLocation(
        string $location,
        string $name = '',
        array $sequences = []
    ): void {
        static::$locations[] = [
            'path' => $location,
            'name' => $name,
            'sequences' => $sequences,
        ];
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
