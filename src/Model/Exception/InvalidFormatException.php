<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Model\Exception;

final class InvalidFormatException extends \RuntimeException
{
    public function __construct(string $data, string $expected)
    {
        parent::__construct("Unexpected call on the payload, data contained are a(n) $data, and $expected expected");
    }
}
