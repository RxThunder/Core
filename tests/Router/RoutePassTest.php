<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Router;

use PHPUnit\Framework\TestCase;
use RxThunder\Core\Router\Route\TataRoute;
use RxThunder\Core\Router\Route\TitiRoute;
use RxThunder\Core\Router\Route\TotoRoute;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RoutePassTest extends TestCase
{
    public function testFindCorrectlyRouteAndInsertThemInRouter(): void
    {
        $container = new ContainerBuilder();

        $definition = $container->register(Router::class);

        $container->register(TotoRoute::class)->addTag('route');
        $container->register(TitiRoute::class)->addTag('route');
        $container->register(TataRoute::class)->addTag('route');

        (new RoutePass())->process($container);

        $method_calls = $definition->getMethodCalls();

        $this->assertEquals(
            ['addRoute', 'addRoute', 'addRoute'],
            array_column($method_calls, 0)
        );

        $this->assertEquals(TotoRoute::class, $method_calls[0][1][0]->__toString());
        $this->assertEquals(TitiRoute::class, $method_calls[1][1][0]->__toString());
        $this->assertEquals(TataRoute::class, $method_calls[2][1][0]->__toString());
    }
}
