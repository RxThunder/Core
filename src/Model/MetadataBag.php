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
    /** @psalm-var array<string, scalar>  */
    private array $parameters = [];

    /**
     * @psalm-param array<string, scalar> $parameters
     */
    public function __construct(array $parameters)
    {
        foreach ($parameters as $key => $value) {
            $this->add((string) $key, $value);
        }
    }

    /**
     * @psalm-param scalar $value
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
     * @psalm-return ?scalar
     */
    public function get(string $key)
    {
        return $this->parameters[$key] ?? null;
    }
}
