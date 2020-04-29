<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\Configuration\Tests\Dao;

use PHPUnit\Framework\TestCase;
use GrizzIt\Configuration\Dao\Locator;

/**
 * @coversDefaultClass \GrizzIt\Configuration\Dao\Locator
 */
class LocatorTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getKey
     * @covers ::getLocation
     */
    public function testLocator(): void
    {
        $subject = new Locator('foo', 'bar');
        $this->assertInstanceOf(Locator::class, $subject);

        $this->assertEquals(
            'foo',
            $subject->getKey()
        );

        $this->assertEquals(
            'bar',
            $subject->getLocation()
        );
    }
}
