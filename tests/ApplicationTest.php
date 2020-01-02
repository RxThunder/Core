<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core;

use PHPUnit\Framework\TestCase;
use RxThunder\Core\Console\DumbConsole;
use RxThunder\Core\Kernel\CustomContainerKernel;
use RxThunder\Core\Kernel\Kernel;
use Silly\Input\InputOption;

final class ApplicationTest extends TestCase
{
    public function testApplicationBoot(): void
    {
        $kernel = new CustomContainerKernel();

        $application = new Application($kernel);

        $this->assertSame(Kernel::NAME, $application->getName());
        $this->assertSame(Kernel::VERSION, $application->getVersion());
        $this->assertInstanceOf(InputOption::class, $application->getDefinition()->getOption('env'));
        $this->assertNotNull($application->getContainer()->get(DumbConsole::class));
    }
}
