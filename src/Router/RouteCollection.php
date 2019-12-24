<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Router;

final class RouteCollection
{
    /** @var array<string, Route> */
    private array $routes = [];

    public function get(string $route_path): ?Route
    {
        return $this->routes[$route_path] ?? null;
    }

    /**
     * @return array<string, Route>
     */
    public function all(): array
    {
        return $this->routes;
    }

    public function add(Route $route): self
    {
        $this->routes[$route->path()] = $route;

        return $this;
    }
}
