<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Router;

use Rx\Observable;
use RxThunder\Core\Model\DataModel;

/**
 * Why route are invokable https://gist.github.com/Ocramius/b3a5c4b5610efc132411
 */
abstract class Route
{
    public const PATH = '/';

    abstract public function __invoke(DataModel $data_model): Observable;

    public function path(): string
    {
        return static::PATH;
    }
}
