<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Router\Route;

use Rx\Observable;
use RxThunder\Core\Model\DataModel;
use RxThunder\Core\Router\Route;

class TotoRoute extends Route
{
    public const PATH = 'toto';

    public function __invoke(DataModel $data_model): Observable
    {
        return Observable::of(1);
    }
}
