<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Logger\Test;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

final class LoggerAwareClass implements LoggerAwareInterface
{
    use LoggerAwareTrait;
}
