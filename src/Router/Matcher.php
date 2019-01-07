<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Router;

use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class Matcher
{
    protected $routes;

    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;
    }

    public function __invoke(AbstractSubject $subject)
    {
        try {
            return (new UrlMatcher($this->routes, new RequestContext()))
                ->match($subject->getRoutingPath())['_controller'];
        } catch (ResourceNotFoundException $e) {
            $subject->onError($e);

            return null;
        }
    }
}
