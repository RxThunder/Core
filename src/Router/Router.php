<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Router;

class Router
{
    protected RouteCollection $collection;
    protected Matcher $matcher;

    public function __construct()
    {
        $this->collection = new RouteCollection();
        $this->matcher    = new Matcher($this->collection);
    }

    public function match(string $route_path): Route
    {
        return $this->matcher->match($route_path);
    }

    public function addRoute(Route $route): self
    {
        $this->collection->add($route);

        return $this;
    }
}
