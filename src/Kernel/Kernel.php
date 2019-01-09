<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Kernel;

use Psr\Container\ContainerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\Dotenv\Dotenv;

class Kernel implements KernelInterface
{
    const NAME = 'Thunder';
    const VERSION = '0.3.0';
    const VERSION_ID = 00300;
    const MAJOR_VERSION = 0;
    const MINOR_VERSION = 3;
    const RELEASE_VERSION = 0;
    const EXTRA_VERSION = '';

    protected $environment;
    protected $container;
    protected $booted = false;
    protected $debug;
    protected $projectDir;

    /**
     * Kernel constructor.
     */
    public function __construct(string $environment, bool $debug = false)
    {
        $this->environment = $environment;
        $this->debug = $debug;
        $this->projectDir = $this->getProjectDir();
    }

    public function boot()
    {
        if ($this->booted) {
            return;
        }

        $this->initializeContainer();

        $this->booted = true;
    }

    public function initializeContainer()
    {
        $container = new ContainerBuilder();

        $this->loadEnvironment();
        $this->loadDefinitions($container);

        $container->compile();
        $this->container = $container;
    }

    protected function loadDefinitions(ContainerBuilder $container)
    {
        $container->setParameter('thunder.environment', $this->getEnvironment());
        $container->setParameter('thunder.debug', $this->isDebug());
        $container->setParameter('thunder.project_dir', $this->getProjectDir());
        $container->setParameter('thunder.config_dir', $this->getConfigDir());

        $internalLoader =
            new PhpFileLoader($container,
                new FileLocator(__DIR__.'/../../config')
            );

        $personalLoader =
            new PhpFileLoader($container,
                new FileLocator($this->getConfigDir())
            );

        $internalLoader->load('console.php');
        $internalLoader->load('container.php');
        $internalLoader->load('eventstore.php');
        $internalLoader->load('router.php');

        $personalLoader->load('parameters.php');
        $personalLoader->load('services.php');

        $internalLoader->load('logger.php');
    }

    protected function loadEnvironment()
    {
        (new Dotenv())->load('.env');
    }

    public function getProjectDir(): string
    {
        if (null === $this->projectDir) {
            $r = new \ReflectionObject($this);

            if ($fileName = $r->getFileName()) {
                $dir = $rootDir = \dirname($fileName);
                while (!file_exists($dir.'/composer.lock')) {
                    if ($dir === \dirname($dir)) {
                        return $this->projectDir = $rootDir;
                    }
                    $dir = \dirname($dir);
                }

                $this->projectDir = $dir;
            }
        }

        return $this->projectDir;
    }

    public function getConfigDir(): string
    {
        return $this->projectDir.'/config';
    }

    public function getEnvironment(): string
    {
        return $this->environment;
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function isDebug(): bool
    {
        return $this->debug;
    }

    public function isBooted(): bool
    {
        return $this->booted;
    }
}
