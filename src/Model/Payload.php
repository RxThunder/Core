<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Model;

use RxThunder\Core\Model\Exception\InvalidFormatException;

final class Payload
{
    /** @psalm-var array<array-key, mixed>|string|null */
    private $data;

    /**
     * @psalm-param array<array-key, mixed>|string|null $data
     */
    public function __construct($data = null)
    {
        $this->data = $data;
    }

    public function dataInStringFormat(): ?string
    {
        if ($this->data === null) {
            return null;
        }

        if (!\is_string($this->data)) {
            throw new InvalidFormatException($this->dataType(), 'string');
        }

        return $this->data;
    }

    /**
     * @psalm-return array<array-key, mixed>|null
     */
    public function dataInArrayFormat(): ?array
    {
        if ($this->data === null) {
            return null;
        }

        if (!\is_array($this->data)) {
            throw new InvalidFormatException($this->dataType(), 'array');
        }

        return $this->data;
    }

    public function dataType(): string
    {
        return \gettype($this->data);
    }
}
