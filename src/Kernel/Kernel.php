<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Kernel;

use RxThunder\Core\Kernel\Exception\KernelNotBootedException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\Dotenv\Dotenv;

class Kernel implements KernelInterface
{
    public const NAME            = 'Thunder';
    public const VERSION         = '1.0.0';
    public const VERSION_ID      = 10000;
    public const MAJOR_VERSION   = 1;
    public const MINOR_VERSION   = 0;
    public const RELEASE_VERSION = 0;
    public const EXTRA_VERSION   = '';

    protected string $environment;
    protected ContainerBuilder $container;
    protected bool $booted = false;
    protected bool $debug;
    protected string $project_dir;

    public function __construct(string $environment, bool $debug = false)
    {
        $this->environment = $environment;
        $this->debug       = $debug;
        $this->project_dir = $this->initiateProjectDir();
    }

    public function boot(): void
    {
        if ($this->booted) {
            return;
        }

        $this->initializeContainer();

        $this->booted = true;
    }

    public function initializeContainer(): void
    {
        $container = new ContainerBuilder();

        $this->loadEnvironment();
        $this->loadDefinitions($container);

        $container->compile();
        $this->container = $container;
    }

    protected function loadDefinitions(ContainerBuilder $container): void
    {
        $container->setParameter('thunder.environment', $this->getEnvironment());
        $container->setParameter('thunder.debug', $this->debugActivated());
        $container->setParameter('thunder.project_dir', $this->getProjectDir());
        $container->setParameter('thunder.config_dir', $this->getConfigDir());

        $internal_loader =
            new PhpFileLoader(
                $container,
                new FileLocator(__DIR__ . '/../../config')
            );

        $personal_loader =
            new PhpFileLoader(
                $container,
                new FileLocator($this->getConfigDir())
            );

        $internal_loader->load('console.php');
        $internal_loader->load('container.php');
        $internal_loader->load('router.php');
        $internal_loader->load('logger.php');

        $personal_loader->load('parameters.php');
        $personal_loader->load('services.php');
    }

    protected function loadEnvironment(): void
    {
        (new Dotenv())->populate(['APP_ENV' => $this->getEnvironment()]);
        (new Dotenv())->loadEnv($this->getProjectDir() . '/.env');
    }

    protected function initiateProjectDir(): string
    {
        if (isset($this->project_dir)) {
            throw new \LogicException('Project directory is already defined');
        }

        $reflected = new \ReflectionObject($this);
        if (!$dir = $reflected->getFileName()) {
            throw new \LogicException(sprintf('Cannot get filename of class "%s".', $reflected->name));
        }

        if (!file_exists($dir)) {
            throw new \LogicException(sprintf('File of class donʼt exists "%s".', $reflected->name));
        }

        $dir = $root_dir = \dirname($dir);
        while (!file_exists($dir . '/composer.lock')) {
            if ($dir === \dirname($dir)) {
                return $root_dir;
            }

            $dir = \dirname($dir);
        }

        return $dir;
    }

    public function getProjectDir(): string
    {
        return $this->project_dir;
    }

    public function getConfigDir(): string
    {
        return $this->project_dir . '/config';
    }

    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * @throws KernelNotBootedException
     */
    public function getContainer(): ContainerBuilder
    {
        if (!$this->booted) {
            throw new KernelNotBootedException();
        }

        return $this->container;
    }

    public function debugActivated(): bool
    {
        return $this->debug;
    }

    public function booted(): bool
    {
        return $this->booted;
    }
}
