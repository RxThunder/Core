<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Kernel\Exception;

class KernelNotBooted extends \LogicException
{
    protected function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function gettingContainer(): self
    {
        return new self('The kernel must be booted before getting the container instance');
    }
}
