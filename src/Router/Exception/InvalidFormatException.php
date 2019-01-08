<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Router\Exception;

final class InvalidFormatException extends \RuntimeException
{
    public function __construct($data, $expected)
    {
        $message = sprintf(
            'Unexpected call on the payload, data contained are a(n) %s, and %s expected',
            \gettype($data),
            $expected
        );

        parent::__construct($message);
    }
}
