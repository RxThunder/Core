<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Kernel;

class CustomProjectDirKernel extends Kernel
{
    public function __construct()
    {
        parent::__construct('test', true);
    }

    public function getProjectDir(): string
    {
        return realpath(__DIR__ . '/../skeleton');
    }

    public function getConfigDir(): string
    {
        return realpath(__DIR__ . '/../skeleton/config');
    }
}
