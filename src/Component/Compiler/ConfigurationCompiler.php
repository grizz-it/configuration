<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\Configuration\Component\Compiler;

use GrizzIt\Vfs\Common\FileSystemDriverInterface;
use GrizzIt\Configuration\Common\LocatorInterface;
use GrizzIt\Configuration\Common\CompilerInterface;
use GrizzIt\Configuration\Common\RegistryInterface;
use GrizzIt\Configuration\Component\Registry\Registry;
use GrizzIt\Configuration\Component\Configuration\PackageLocator;

class ConfigurationCompiler implements CompilerInterface
{
    /**
     * Contains the locators.
     *
     * @var LocatorInterface[]
     */
    private $locators = [];

    /**
     * Contains the driver for traversing over the files.
     *
     * @var FileSystemDriverInterface
     */
    private $driver;

    /**
     * Contains the registry in which the configuration will be stored.
     *
     * @var RegistryInterface
     */
    private $registry;

    /**
     * Constructor
     *
     * @param FileSystemDriverInterface $driver
     */
    public function __construct(
        FileSystemDriverInterface $driver,
        RegistryInterface $registry = null
    ) {
        $this->driver = $driver;
        $this->registry = $registry ?? new Registry();
    }

    /**
     * Adds a locator to the compiler.
     *
     * @param LocatorInterface $locator
     *
     * @return void
     */
    public function addLocator(LocatorInterface $locator): void
    {
        $this->locators[] = $locator;
    }

    /**
     * Compiles configuration into an array of data.
     *
     * @return RegistryInterface
     */
    public function compile(): RegistryInterface
    {
        $packages = PackageLocator::getLocations();
        $normalizer = $this->driver->getFileSystemNormalizer();

        foreach ($packages as $package) {
            $fileSystem = $this->driver->connect($package);

            foreach ($this->locators as $locator) {
                $directory = rtrim($locator->getLocation(), '/');

                foreach ($fileSystem->list($directory) as $file) {
                    $file = sprintf('%s/%s', $directory, ltrim($file, '/'));

                    if ($fileSystem->isReadable($file)) {
                        $configuration = $normalizer->normalizeFromFile(
                            $fileSystem,
                            $file
                        );

                        $this->registry->register(
                            $locator->getKey(),
                            $configuration
                        );
                    }
                }
            }

            $this->driver->disconnect($fileSystem);
        }

        return $this->registry;
    }
}
