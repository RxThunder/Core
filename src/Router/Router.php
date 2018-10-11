<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Th3Mouk\Thunder\Router;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class Router
{
    protected $collection;
    protected $matcher;

    public function __construct()
    {
        $this->collection = new RouteCollection();
        $this->matcher = new Matcher($this->collection);
    }

    public function __invoke(AbstractSubject $value)
    {
        if (null === $handler = ($this->matcher)($value)) {
            return;
        }

        $handler($value);
    }

    public function addRoute(AbstractRoute $route)
    {
        $this->collection->add(
            \get_class($route),
            new Route($route->getRoutePath(), ['_controller' => $route])
        );

        return $this;
    }
}
