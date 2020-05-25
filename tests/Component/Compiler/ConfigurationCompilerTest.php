<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\Configuration\Tests\Component\Compiler;

use RuntimeException;
use PHPUnit\Framework\TestCase;
use GrizzIt\Vfs\Common\FileSystemInterface;
use GrizzIt\Vfs\Common\FileSystemDriverInterface;
use GrizzIt\Configuration\Common\LocatorInterface;
use GrizzIt\Configuration\Common\RegistryInterface;
use GrizzIt\Vfs\Common\FileSystemNormalizerInterface;
use GrizzIt\Configuration\Component\Configuration\PackageLocator;
use GrizzIt\Configuration\Component\Compiler\ConfigurationCompiler;

/**
 * @coversDefaultClass \GrizzIt\Configuration\Component\Compiler\ConfigurationCompiler
 */
class ConfigurationCompilerTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::addLocator
     * @covers ::compile
     * @covers ::orderPackages
     *
     * @runInSeparateProcess
     */
    public function testCompiler(): void
    {
        $driver = $this->createMock(FileSystemDriverInterface::class);
        $subject = new ConfigurationCompiler($driver);

        PackageLocator::registerLocation(__DIR__, 'Foo', ['Bar']);
        PackageLocator::registerLocation(__DIR__);
        PackageLocator::registerLocation(__DIR__, 'Bar');

        $this->assertInstanceOf(ConfigurationCompiler::class, $subject);

        $locator = $this->createMock(LocatorInterface::class);

        $subject->addLocator($locator);

        $fileSystem = $this->createMock(FileSystemInterface::class);

        $driver->expects(static::exactly(3))
            ->method('connect')
            ->with(__DIR__)
            ->willReturn($fileSystem);

        $locator->expects(static::exactly(3))
            ->method('getLocation')
            ->willReturn('configuration/');

        $fileSystem->expects(static::exactly(3))
            ->method('list')
            ->with('configuration')
            ->willReturn(['/foo.json']);

        $fileSystem->expects(static::exactly(1))
            ->method('isReadable')
            ->with('configuration/foo.json')
            ->willReturn(true);

        $fileSystemNormalizer = $this->createMock(
            FileSystemNormalizerInterface::class
        );

        $driver->expects(static::once())
            ->method('getFileSystemNormalizer')
            ->willReturn($fileSystemNormalizer);

        $fileSystemNormalizer->expects(static::once())
            ->method('normalizeFromFile')
            ->with($fileSystem, 'configuration/foo.json')
            ->willReturn(['foo' => 'bar']);

        $locator->expects(static::once())
            ->method('getKey')
            ->willReturn('foo');

        $result = $subject->compile();

        $this->assertInstanceOf(RegistryInterface::class, $result);

        $this->assertEquals(
            ['foo' => [['foo' => 'bar']]],
            $result->toArray()
        );
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::addLocator
     * @covers ::compile
     * @covers ::orderPackages
     *
     * @runInSeparateProcess
     */
    public function testCompilerException(): void
    {
        $driver = $this->createMock(FileSystemDriverInterface::class);
        $subject = new ConfigurationCompiler($driver);

        PackageLocator::registerLocation(__DIR__, 'Foo', ['Bar']);
        PackageLocator::registerLocation(__DIR__, 'Bar', ['Foo']);

        $this->assertInstanceOf(ConfigurationCompiler::class, $subject);

        $locator = $this->createMock(LocatorInterface::class);

        $subject->addLocator($locator);

        $fileSystem = $this->createMock(FileSystemInterface::class);

        $this->expectException(RuntimeException::class);

        $result = $subject->compile();
    }
}
