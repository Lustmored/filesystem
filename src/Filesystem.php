<?php

/*
 * This file is part of the zenstruck/filesystem package.
 *
 * (c) Kevin Bond <kevinbond@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zenstruck;

use Zenstruck\Filesystem\Exception\NodeNotFound;
use Zenstruck\Filesystem\Exception\NodeTypeMismatch;
use Zenstruck\Filesystem\Node;
use Zenstruck\Filesystem\Node\Directory;
use Zenstruck\Filesystem\Node\File;
use Zenstruck\Filesystem\Node\File\Image;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
interface Filesystem
{
    public function name(): string;

    /**
     * @throws NodeNotFound if the path does not exist
     */
    public function node(string $path): Node;

    /**
     * @throws NodeNotFound     if the path does not exist
     * @throws NodeTypeMismatch if the node at path is not a file
     */
    public function file(string $path): File;

    /**
     * @throws NodeNotFound     if the path does not exist
     * @throws NodeTypeMismatch if the node at path is not a directory
     */
    public function directory(string $path = ''): Directory;

    /**
     * @throws NodeNotFound     if the path does not exist
     * @throws NodeTypeMismatch if the node at path is not an image file
     */
    public function image(string $path): Image;

    public function has(string $path): bool;

    public function copy(string $source, string $destination, array $config = []): File;

    public function move(string $source, string $destination, array $config = []): File;

    public function delete(string $path, array $config = []): static;

    /**
     * @param array{
     *     progress?: callable(File=):void
     * } $config
     */
    public function mkdir(string $path, Directory|\SplFileInfo|null $content = null, array $config = []): Directory;

    public function chmod(string $path, string $visibility): Node;

    /**
     * @param resource|string|\SplFileInfo|File $value
     */
    public function write(string $path, mixed $value, array $config = []): File;
}
