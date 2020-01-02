<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Console;

use RxThunder\Core\Console;

final class DumbConsole extends Console
{
    public static string $expression = 'dumb';

    public static string $description = 'do nothing';
}
