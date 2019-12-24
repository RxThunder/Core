<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Kernel;

use Symfony\Component\DependencyInjection\ContainerBuilder;

interface KernelInterface
{
    public function getProjectDir(): string;

    public function getConfigDir(): string;

    public function initializeContainer(): void;

    public function boot(): void;

    public function getEnvironment(): string;

    public function getContainer(): ContainerBuilder;

    public function debugActivated(): bool;

    public function booted(): bool;
}
