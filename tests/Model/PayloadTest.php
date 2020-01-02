<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Model;

use PHPUnit\Framework\TestCase;
use RxThunder\Core\Model\Exception\InvalidFormatException;

final class PayloadTest extends TestCase
{
    public function testReturnNullWhenEmptyPayload(): void
    {
        $payload = new Payload();

        $this->assertSame(null, $payload->dataInArrayFormat());
        $this->assertSame(null, $payload->dataInStringFormat());
        $this->assertSame('NULL', $payload->dataType());
    }

    public function testStringPayload(): void
    {
        $payload = new Payload('string');

        $this->assertSame('string', $payload->dataInStringFormat());
        $this->assertSame('string', $payload->dataType());
    }

    public function testArrayPayload(): void
    {
        $payload = new Payload(['array' => 'array']);

        $this->assertSame(['array' => 'array'], $payload->dataInArrayFormat());
        $this->assertSame('array', $payload->dataType());
    }

    public function testThrowWhenAskingArrayDataOnStringPayload(): void
    {
        $payload = new Payload('string');

        $this->expectException(InvalidFormatException::class);
        $this->expectExceptionMessage('Unexpected call on the payload, data contained are a(n) string, and array expected');

        $payload->dataInArrayFormat();
    }

    public function testThrowWhenAskingStringDataOnArrayPayload(): void
    {
        $payload = new Payload(['array' => 'array']);

        $this->expectException(InvalidFormatException::class);
        $this->expectExceptionMessage('Unexpected call on the payload, data contained are a(n) array, and string expected');

        $payload->dataInStringFormat();
    }
}
