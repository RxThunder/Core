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

final class MatcherTest extends TestCase
{
    public function testMatch(): void
    {
        $collection = new RouteCollection();
        $collection->add($route = new TotoRoute());

        $matcher = new Matcher($collection);

        $route_found = $matcher->match('toto');

        $this->assertSame($route, $route_found);
    }

    public function testNoResultThrowing(): void
    {
        $collection = new RouteCollection();

        $matcher = new Matcher($collection);

        $this->expectException(RouteNotFoundException::class);

        $matcher->match(TotoRoute::PATH);
    }
}
