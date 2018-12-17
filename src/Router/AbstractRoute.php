<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Th3Mouk\Thunder\Router;

abstract class AbstractRoute
{
    public const PATH = '/';

    abstract public function __invoke(AbstractSubject $subject);

    public function getRoutePath(): string
    {
        return static::PATH;
    }
}
