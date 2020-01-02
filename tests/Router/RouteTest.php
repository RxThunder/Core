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
use RxThunder\Core\Router\Route\TotoRoute;

final class RouteTest extends TestCase
{
    public function testRoutingPathIsExact(): void
    {
        $route = new TotoRoute();

        $this->assertEquals($route->path(), TotoRoute::PATH);
    }
}
