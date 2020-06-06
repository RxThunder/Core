<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Router;

use RxThunder\Core\Router\Exception\RouteNotFoundException;

class Matcher
{
    protected RouteCollection $routes;

    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;
    }

    public function match(string $type): Route
    {
        if ($route = $this->routes->get($type)) {
            return $route;
        }

        throw new RouteNotFoundException($type);
    }
}
