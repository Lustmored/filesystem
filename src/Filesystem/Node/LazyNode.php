<?php

/*
 * This file is part of the zenstruck/filesystem package.
 *
 * (c) Kevin Bond <kevinbond@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zenstruck\Filesystem\Node;

use Zenstruck\Filesystem;
use Zenstruck\Filesystem\Node;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class LazyNode implements Node
{
    use DecoratedNode;

    protected Node $inner;

    /** @var null|Filesystem|callable():Filesystem */
    private $filesystem;

    /** @var Path|string|callable():string */
    private $path;

    /**
     * @param string|callable():string $path
     */
    public function __construct(string|callable $path)
    {
        $this->path = $path;
    }

    /**
     * @param Filesystem|callable():Filesystem $filesystem
     */
    public function setFilesystem(Filesystem|callable $filesystem): void
    {
        $this->filesystem = $filesystem;
    }

    public function path(): Path
    {
        if ($this->path instanceof Path) {
            return $this->path;
        }

        if (\is_callable($this->path)) {
            return $this->path = new Path(($this->path)());
        }

        return $this->path = new Path($this->path);
    }

    protected function filesystem(): Filesystem
    {
        if ($this->filesystem instanceof Filesystem) {
            return $this->filesystem;
        }

        if (\is_callable($this->filesystem)) {
            return $this->filesystem = ($this->filesystem)();
        }

        throw new \RuntimeException('Filesystem not set.');
    }
}