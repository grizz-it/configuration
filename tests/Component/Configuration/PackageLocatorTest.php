<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\Configuration\Tests\Component\Configuration;

use PHPUnit\Framework\TestCase;
use GrizzIt\Configuration\Component\Configuration\PackageLocator;

/**
 * @coversDefaultClass \GrizzIt\Configuration\Component\Configuration\PackageLocator
 */
class PackageLocatorTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::registerLocation
     * @covers ::getLocations
     */
    public function testPackageLocator(): void
    {
        PackageLocator::registerLocation('foo');
        PackageLocator::registerLocation('bar');
        $this->assertContains('foo', PackageLocator::getLocations());
        $this->assertContains('bar', PackageLocator::getLocations());
    }
}
