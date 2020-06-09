<?php

declare(strict_types=1);

namespace RxThunder\Core\Extension;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

final class DummyExtension extends Extension
{
    /**
     * @param array<array-key, mixed> $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $container->setParameter('extension.dummy', true);

        return;
    }
}
