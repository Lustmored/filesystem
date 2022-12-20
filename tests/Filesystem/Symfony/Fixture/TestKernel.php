<?php

/*
 * This file is part of the zenstruck/filesystem package.
 *
 * (c) Kevin Bond <kevinbond@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zenstruck\Tests\Filesystem\Symfony\Fixture;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Zenstruck\Filesystem\Symfony\ZenstruckFilesystemBundle;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class TestKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        yield new FrameworkBundle();
        yield new ZenstruckFilesystemBundle();
    }

    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader): void
    {
        $c->loadFromExtension('framework', [
            'http_method_override' => false,
            'secret' => 'S3CRET',
            'router' => ['utf8' => true],
            'test' => true,
        ]);

        $c->loadFromExtension('zenstruck_filesystem', [
            'filesystems' => [
                'public' => [
                    'dsn' => '%kernel.project_dir%/var/public',
                    'url_prefix' => '/files',
                ],
                'private' => [
                    'dsn' => '%kernel.project_dir%/var/private',
                ],
                'no_reset' => [
                    'dsn' => '%kernel.project_dir%/var/no_reset',
                    'test' => false,
                ],
            ],
        ]);

        $c->register(Service::class)
            ->setPublic(true)
            ->setAutowired(true)
        ;
    }
}
