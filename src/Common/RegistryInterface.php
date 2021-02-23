<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\Configuration\Common;

interface RegistryInterface
{
    /**
     * Register a value in the registry.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function register(string $key, mixed $value): void;

    /**
     * Converts the registry to an array.
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Imports a registry from an array into the registry.
     *
     * @param array $registry
     *
     * @return void
     */
    public function import(array $registry): void;

    /**
     * Retrieves all registered data associated with a key.
     *
     * @param string $key
     *
     * @return array
     */
    public function get(string $key): array;
}
