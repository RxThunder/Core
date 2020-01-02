<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Model\Exception;

use PHPUnit\Framework\TestCase;
use RxThunder\Core\Model\DataModel;

final class DataModelExceptionTest extends TestCase
{
    public function testProperties(): void
    {
        $data_model = new DataModel('toto');
        $exception  = new DataModelException($data_model);

        $this->assertSame($data_model, $exception->dataModel());
    }
}
