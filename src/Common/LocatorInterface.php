<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\Configuration\Common;

interface LocatorInterface
{
    /**
     * Retrieves the location in which configuration needs to be fetched.
     *
     * @return string
     */
    public function getLocation(): string;

    /**
     * Retrieves the key for configuration.
     *
     * @return string
     */
    public function getKey(): string;
}
