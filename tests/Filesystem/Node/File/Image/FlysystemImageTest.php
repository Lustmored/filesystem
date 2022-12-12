<?php

namespace Zenstruck\Tests\Filesystem\Node\File\Image;

use Zenstruck\Filesystem\Node\File\Image;
use Zenstruck\Tests\Filesystem\Node\File\ImageTest;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class FlysystemImageTest extends ImageTest
{
    protected function createFile(\SplFileInfo $file, string $path): Image
    {
        return $this->filesystem->write($path, $file)->last()->ensureImage();
    }
}
