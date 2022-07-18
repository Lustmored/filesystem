<?php

namespace Zenstruck\Filesystem;

use League\Flysystem\FilesystemOperator;
use League\Flysystem\StorageAttributes;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class Node
{
    private string $path;
    private \DateTimeImmutable $lastModified;
    private string $visibility;

    public function __construct(StorageAttributes $attributes, protected FilesystemOperator $flysystem)
    {
        $this->path = $attributes->path();

        if ($lastModified = $attributes->lastModified()) {
            $this->lastModified = self::dateTimeFrom((string) $lastModified);
        }

        if ($visibility = $attributes->visibility()) {
            $this->visibility = $visibility;
        }
    }

    public function __toString(): string
    {
        return $this->path;
    }

    final public function path(): string
    {
        return $this->path;
    }

    /**
     * Returns the file or directory name (with extension if applicable).
     *
     * @example If $path is "foo/bar/baz.txt", returns "baz.txt"
     * @example If $path is "foo/bar/baz", returns "baz"
     */
    final public function name(): string
    {
        return \pathinfo($this->path(), \PATHINFO_BASENAME);
    }

    /**
     * Returns the "parent" directory path.
     *
     * @example If $path is "foo/bar/baz", returns "foo/bar"
     */
    final public function dirname(): string
    {
        return \pathinfo($this->path(), \PATHINFO_DIRNAME);
    }

    final public function lastModified(): \DateTimeImmutable
    {
        return $this->lastModified ??= self::dateTimeFrom((string) $this->flysystem->lastModified($this->path()));
    }

    public function visibility(): string
    {
        return $this->visibility ??= $this->flysystem->visibility($this->path());
    }

    private static function dateTimeFrom(string $timestamp): \DateTimeImmutable
    {
        return \DateTimeImmutable::createFromFormat('U', $timestamp) // @phpstan-ignore-line
            // timestamp is always in UTC so convert to current system timezone
            ->setTimezone(new \DateTimeZone(\date_default_timezone_get()))
        ;
    }
}
