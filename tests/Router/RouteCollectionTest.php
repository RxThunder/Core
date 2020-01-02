<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Router;

use PHPUnit\Framework\TestCase;
use RxThunder\Core\Router\Route\TataRoute;
use RxThunder\Core\Router\Route\TitiRoute;
use RxThunder\Core\Router\Route\TotoRoute;

final class RouteCollectionTest extends TestCase
{
    public function testCollectionBehaviorIsCorrect(): void
    {
        $collection = new RouteCollection();

        $this->assertNull($collection->get('test'));

        $collection->add(new TotoRoute());
        $collection->add(new TitiRoute());
        $collection->add(new TataRoute());

        $this->assertInstanceOf(TotoRoute::class, $collection->get(TotoRoute::PATH));
        $this->assertInstanceOf(TataRoute::class, $collection->get(TataRoute::PATH));
        $this->assertInstanceOf(TitiRoute::class, $collection->get(TitiRoute::PATH));

        $this->assertCount(3, $collection->all());
    }
}
