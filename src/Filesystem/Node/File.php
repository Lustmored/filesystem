<?php

namespace Zenstruck\Filesystem\Node;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemOperator;
use Zenstruck\Filesystem\Node;
use Zenstruck\Filesystem\Node\File\Size;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class File extends Node
{
    private Size $size;
    private string $mimeType;

    public function __construct(FileAttributes $attributes, FilesystemOperator $flysystem)
    {
        parent::__construct($attributes, $flysystem);

        if ($size = $attributes->fileSize()) {
            $this->size = Size::binary($size);
        }

        if ($mimeType = $attributes->mimeType()) {
            $this->mimeType = $mimeType;
        }
    }

    public function size(): Size
    {
        return $this->size ??= Size::binary($this->flysystem->fileSize($this->path()));
    }

    public function mimeType(): string
    {
        return $this->mimeType ??= $this->flysystem->mimeType($this->path());
    }

    public function contents(): string
    {
        return $this->flysystem->read($this->path());
    }

    /**
     * @return resource
     */
    public function read()
    {
        return $this->flysystem->readStream($this->path());
    }

    /**
     * @return Directory<Node>
     */
    public function directory(): Directory
    {
        return new Directory(new DirectoryAttributes($this->dirname()), $this->flysystem);
    }

    public function extension(): ?string
    {
        return \pathinfo($this->path(), \PATHINFO_EXTENSION) ?: null;
    }

    public function refresh(): Node
    {
        unset($this->size, $this->mimeType);

        return parent::refresh();
    }
}
