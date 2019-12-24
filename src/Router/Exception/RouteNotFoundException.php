<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Router\Exception;

final class RouteNotFoundException extends \RuntimeException
{
    public function __construct(string $route)
    {
        parent::__construct("Route {$route} has not been found by the matcher in the router");
    }
}
