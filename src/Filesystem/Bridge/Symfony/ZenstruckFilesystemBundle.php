<?php

namespace Zenstruck\Filesystem\Bridge\Symfony;

use Doctrine\DBAL\Types\Type;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Zenstruck\Filesystem\Bridge\Doctrine\DBAL\Types\FileCollectionType;
use Zenstruck\Filesystem\Bridge\Doctrine\DBAL\Types\FileType;
use Zenstruck\Filesystem\Bridge\Doctrine\DBAL\Types\ImageCollectionType;
use Zenstruck\Filesystem\Bridge\Doctrine\DBAL\Types\ImageType;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class ZenstruckFilesystemBundle extends Bundle
{
    public function boot(): void
    {
        parent::boot();

        if (!\class_exists(Type::class)) {
            return;
        }

        foreach ([FileType::class, ImageType::class, FileCollectionType::class, ImageCollectionType::class] as $type) {
            if (!Type::hasType($type::NAME)) {
                Type::addType($type::NAME, $type);
            }
        }
    }
}