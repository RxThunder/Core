<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Router;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RoutePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $router_definition = $container->getDefinition(Router::class);
        $routes            = $container->findTaggedServiceIds('route');
        foreach ($routes as $service_id => $tag_attributes) {
            $router_definition->addMethodCall('addRoute', [new Reference($service_id)]);
        }
    }
}
