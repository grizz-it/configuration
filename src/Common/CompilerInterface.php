<?php
/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace GrizzIt\Configuration\Common;

interface CompilerInterface
{
    /**
     * Adds a locator to the compiler.
     *
     * @param LocatorInterface $locator
     *
     * @return void
     */
    public function addLocator(LocatorInterface $locator): void;

    /**
     * Compiles configuration into an array of data.
     *
     * @return RegistryInterface
     */
    public function compile(): RegistryInterface;
}
