<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\Configuration\Component\Compiler;

use RuntimeException;
use GrizzIt\Vfs\Common\FileSystemInterface;
use GrizzIt\Vfs\Common\FileSystemDriverInterface;
use GrizzIt\Configuration\Common\LocatorInterface;
use GrizzIt\Configuration\Common\CompilerInterface;
use GrizzIt\Configuration\Common\RegistryInterface;
use GrizzIt\Vfs\Common\FileSystemNormalizerInterface;
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
     * Contains a list of files which have been loaded previously and should not be loaded again.
     *
     * @var string[]
     */
    private $ignoreFiles = [];

    /**
     * Contains the nesting limit for loading sequences.
     *
     * @var int
     */
    private $nestingLimit;

    /**
     * Constructor
     *
     * @param FileSystemDriverInterface $driver
     * @param RegistryInterface $registy
     * @param int $nestingLimit
     */
    public function __construct(
        FileSystemDriverInterface $driver,
        RegistryInterface $registry = null,
        int $nestingLimit = 256
    ) {
        $this->driver = $driver;
        $this->registry = $registry ?? new Registry();
        $this->nestingLimit = 256;
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
        $packages = $this->orderPackages(PackageLocator::getLocations());
        $normalizer = $this->driver->getFileSystemNormalizer();

        foreach ($packages as $package) {
            $fileSystem = $this->driver->connect($package['path']);
            foreach ($this->locators as $locator) {
                $directory = rtrim($locator->getLocation(), '/');
                $this->processDirectory(
                    $directory,
                    $normalizer,
                    $fileSystem,
                    $locator
                );
            }

            $this->driver->disconnect($fileSystem);
        }

        $this->ignoreFiles = [];

        return $this->registry;
    }

    /**
     * Processes a directory.
     *
     * @param string $directory
     * @param FileSystemNormalizerInterface $normalizer
     * @param FileSystemInterface $fileSystem
     * @param LocatorInterface $locator
     *
     * @return void
     */
    private function processDirectory(
        string $directory,
        FileSystemNormalizerInterface $normalizer,
        FileSystemInterface $fileSystem,
        LocatorInterface $locator
    ): void {
        foreach ($fileSystem->list($directory) as $file) {
            $file = sprintf('%s/%s', $directory, ltrim($file, '/'));
            if (!in_array($file, $this->ignoreFiles)) {
                if ($fileSystem->isReadable($file)) {
                    if ($fileSystem->isDirectory($file)) {
                        $this->processDirectory(
                            $file,
                            $normalizer,
                            $fileSystem,
                            $locator
                        );

                        continue;
                    }

                    $configuration = $normalizer->normalizeFromFile(
                        $fileSystem,
                        $file
                    );

                    $this->registry->register(
                        $locator->getKey(),
                        $configuration
                    );

                    $this->ignoreFiles[] = $file;
                }
            }
        }
    }

    /**
     * Orders packages based on the sequences.
     *
     * @param array $packages
     *
     * @return array
     *
     * @throws RuntimeException When the maximum nesting level is exceeded.
     */
    private function orderPackages(array $packages): array
    {
        $sortable = [];
        $unsortable = [];
        foreach ($packages as $package) {
            if (!empty($package['name'])) {
                $sortable[] = $package;

                continue;
            }

            $unsortable[] = $package;
        }

        $sorted = [];
        $sortedCount = count($sortable);
        $pass = 0;
        while (count($sorted) !== $sortedCount) {
            if ($pass >= $this->nestingLimit) {
                throw new RuntimeException(
                    'Maximum nesting level passed, for sequenced configuration.'
                );
            }

            foreach ($sortable as $key => $package) {
                if (empty($package['sequences'])) {
                    $sorted[] = $package;
                    unset($sortable[$key]);

                    continue;
                }

                $sortedNames = array_column($sorted, 'name');
                $add = true;
                foreach ($package['sequences'] as $sequence) {
                    if (!in_array($sequence, $sortedNames)) {
                        $add = false;
                    }
                }

                if ($add) {
                    $sorted[] = $package;
                    unset($sortable[$key]);
                }
            }

            $pass++;
        }

        return array_merge(array_reverse($sorted), $unsortable);
    }
}
