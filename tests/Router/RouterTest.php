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
use RxThunder\Core\Router\Exception\RouteNotFoundException;
use RxThunder\Core\Router\Route\TotoRoute;

class RouterTest extends TestCase
{
    public function testMatch(): void
    {
        $router = new Router();

        $route = new TotoRoute();

        $router->addRoute($route);

        $route_found = $router->match(TotoRoute::PATH);

        $this->assertSame($route, $route_found);
    }

    public function testNoMatchThrow(): void
    {
        $router = new Router();

        $route = new TotoRoute();

        $router->addRoute($route);

        $this->expectException(RouteNotFoundException::class);

        $router->match('something_wrong');
    }
}
