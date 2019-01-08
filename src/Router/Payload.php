<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Router;

use RxThunder\Core\Router\Exception\InvalidFormatException;

final class Payload
{
    private $data;

    public function __construct($data = null)
    {
        $this->data = $data;
    }

    public function getStringData(): ?string
    {
        if ($this->data && !\is_string($this->data)) {
            throw new InvalidFormatException($this->data, 'string');
        }

        return $this->data;
    }

    public function getArrayData(): ?array
    {
        if ($this->data && !\is_array($this->data)) {
            throw new InvalidFormatException($this->data, 'array');
        }

        return $this->data;
    }

    public function getDataType(): string
    {
        return \gettype($this->data);
    }
}
