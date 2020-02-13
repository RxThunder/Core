<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Logger;

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use RxThunder\Core\Logger\Test\LoggerAwareClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class LoggerPassTest extends TestCase
{
    public function testFindCorrectlyClassAndInsertLogger(): void
    {
        $container = new ContainerBuilder();

        $container->register('logger', NullLogger::class);

        $definition = $container->register(LoggerAwareClass::class)->addTag(LoggerPass::TAG);

        (new LoggerPass())->process($container);

        $this->assertEquals(
            ['setLogger'],
            array_column($definition->getMethodCalls(), 0)
        );
    }
}
