<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Kernel;

use RxThunder\Core\Console\DumbConsole;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class CustomContainerKernel extends CustomProjectDirKernel
{
    public function getContainer(): ContainerBuilder
    {
        $container = new ContainerBuilder();

        $container->register(DumbConsole::class, DumbConsole::class)
            ->setPublic(true)
            ->setAutowired(true)
            ->setAutoconfigured(true)
            ->addTag('console');

        $container->compile();

        return $container;
    }
}
