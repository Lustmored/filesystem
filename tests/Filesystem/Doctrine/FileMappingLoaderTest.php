<?php

/*
 * This file is part of the zenstruck/filesystem package.
 *
 * (c) Kevin Bond <kevinbond@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zenstruck\Tests\Filesystem\Doctrine;

use Zenstruck\Filesystem\Doctrine\FileMappingLoader;
use Zenstruck\Filesystem\Node\File\Image\LazyImage;
use Zenstruck\Filesystem\Node\File\LazyFile;
use Zenstruck\Tests\Filesystem\Symfony\Fixture\Entity\Entity2;

use function Zenstruck\Foundry\repository;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class FileMappingLoaderTest extends DoctrineTestCase
{
    /**
     * @test
     */
    public function can_load_files_for_object(): void
    {
        $object = new Entity2('FoO');
        $object->setFile1($this->filesystem()->write('some/file.txt', 'content1')->last());
        $object->setImage1($this->filesystem()->write('some/image.png', 'content2')->last()->ensureImage());

        $this->filesystem()->write('foo.txt', 'content3');
        $this->filesystem()->write('foo.jpg', 'content4');

        $this->em()->persist($object);
        $this->em()->flush();
        $this->em()->clear();

        $fromDb = repository(Entity2::class)->first()->object();
        $fromDb = self::getContainer()->get(FileMappingLoader::class)($fromDb);
        $fromDb = self::getContainer()->get(FileMappingLoader::class)($fromDb); // ensure multiple calls work

        $this->assertInstanceOf(LazyFile::class, $fromDb->getFile1());
        $this->assertSame('content1', $fromDb->getFile1()->contents());
        $this->assertInstanceOf(LazyImage::class, $fromDb->getImage1());
        $this->assertSame('content2', $fromDb->getImage1()->contents());
        $this->assertInstanceOf(LazyFile::class, $fromDb->getVirtualFile1());
        $this->assertSame('content3', $fromDb->getVirtualFile1()->contents());
        $this->assertInstanceOf(LazyImage::class, $fromDb->getVirtualImage1());
        $this->assertSame('content4', $fromDb->getVirtualImage1()->contents());
    }
}