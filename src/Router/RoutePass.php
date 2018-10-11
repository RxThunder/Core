<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Th3Mouk\Thunder\Router;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RoutePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $routerDefinition = $container->getDefinition(Router::class);
        $routes = $container->findTaggedServiceIds('route');
        foreach ($routes as $serviceId => $tagAttributes) {
            $routerDefinition->addMethodCall('addRoute', [new Reference($serviceId)]);
        }
    }
}
