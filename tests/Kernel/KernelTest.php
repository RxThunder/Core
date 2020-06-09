<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Kernel;

use PHPUnit\Framework\TestCase;
use RxThunder\Core\Kernel\Exception\KernelNotBootedException;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class KernelTest extends TestCase
{
    public function testKernelBehaviorIsCorrect(): void
    {
        $kernel = new CustomProjectDirKernel();

        $this->assertSame('test', $kernel->getEnvironment());
        $this->assertSame(true, $kernel->debugActivated());
        $this->assertSame(false, $kernel->booted());

        $kernel->boot();
        // Testing second time do nothing
        $kernel->boot();

        $container = $kernel->getContainer();

        $this->assertSame(true, $kernel->booted());
        $this->assertTrue($container->getParameter('extension.dummy'));
        $this->assertInstanceOf(ContainerBuilder::class, $container);
    }

    public function testProjectDir(): void
    {
        $kernel = new Kernel('test', true);

        $this->assertSame(realpath(__DIR__ . '/../..'), $kernel->getProjectDir());
        $this->assertSame(realpath(__DIR__ . '/../../config'), $kernel->getConfigDir());
    }

    public function testThrowWhenAccedingNotBootedContainer(): void
    {
        $kernel = new Kernel('test', true);

        $this->expectException(KernelNotBootedException::class);

        $kernel->getContainer();
    }

    public function testCallingTwiceProjectDirMethodThrowing(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Project directory is already defined');
        $kernel = new CustomKernelThrowingOnProjectDirDoubleCall();

        $this->assertSame(realpath(__DIR__ . '/../..'), $kernel->getProjectDir());
    }
}
