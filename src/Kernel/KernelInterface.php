<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Kernel;

use Psr\Container\ContainerInterface;

interface KernelInterface
{
    public function getProjectDir(): string;

    public function getConfigDir(): string;

    public function initializeContainer();

    public function boot();

    public function getEnvironment(): string;

    public function getContainer(): ContainerInterface;

    public function isDebug(): bool;

    public function isBooted(): bool;
}
