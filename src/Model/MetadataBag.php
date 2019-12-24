<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Model;

final class MetadataBag
{
    /** @var array<string, bool|int|float|string>  */
    private array $parameters = [];

    /**
     * @param array<mixed, mixed> $parameters
     */
    public function __construct(array $parameters)
    {
        foreach ($parameters as $key => $value) {
            $this->add((string) $key, $value);
        }
    }

    /**
     * @param bool|int|float|string $value
     */
    public function add(string $key, $value): self
    {
        if (!is_scalar($value)) {
            throw new \InvalidArgumentException(sprintf(
                'MetadataBag expect scalar value, %s given',
                gettype($value)
            ));
        }

        $this->parameters[$key] = $value;

        return $this;
    }

    /**
     * @return bool|int|float|string
     */
    public function get(string $key)
    {
        return $this->parameters[$key] ?? null;
    }
}
