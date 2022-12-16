<?php

/*
 * This file is part of the zenstruck/filesystem package.
 *
 * (c) Kevin Bond <kevinbond@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zenstruck\Tests\Filesystem\Test\InteractsWithFilesystem;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Tests\Filesystem\Test\InteractsWithFilesystemTests;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class KernelTest extends KernelTestCase
{
    use InteractsWithFilesystemTests;

    /**
     * @before
     */
    public function _resetFilesystems(): void
    {
        $this->markTestIncomplete('bundle not complete');
    }
}
