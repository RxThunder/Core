<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Logger;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class LoggerPass implements CompilerPassInterface
{
    public const TAG = 'logger.aware';

    public function process(ContainerBuilder $container): void
    {
        $tagged_services = $container->findTaggedServiceIds(self::TAG);

        foreach ($tagged_services as $id => $tags) {
            $definition = $container->findDefinition($id);
            $definition->addMethodCall('setLogger', [new Reference('logger')]);
        }
    }
}
