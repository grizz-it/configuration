<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\Configuration\Component\Registry;

use GrizzIt\Configuration\Common\RegistryInterface;

class Registry implements RegistryInterface
{
    /**
     * Contains all registered values in the registry.
     *
     * @var array
     */
    private array $registry = [];

    /**
     * Register a value in the registry.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function register(string $key, mixed $value): void
    {
        if (
            !isset($this->registry[$key])
            || !in_array($value, $this->registry[$key])
        ) {
            $this->registry[$key][] = $value;
        }
    }

    /**
     * Converts the registry to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->registry;
    }

    /**
     * Imports a registry from an array into the registry.
     *
     * @param array $registry
     *
     * @return void
     */
    public function import(array $registry): void
    {
        $this->registry = array_merge_recursive(
            $this->registry,
            $registry
        );
    }

    /**
     * Retrieves all registered data associated with a key.
     *
     * @param string $key
     *
     * @return array
     */
    public function get(string $key): array
    {
        return $this->registry[$key] ?? [];
    }
}
