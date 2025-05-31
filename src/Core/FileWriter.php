<?php

namespace ResolutoDigital\DevChat\Core;

use Illuminate\Support\Facades\File;

class FileWriter
{
    public function write(string $relativePath, string $content): void
    {
        $path = base_path($relativePath);
        File::ensureDirectoryExists(dirname($path));
        File::put($path, $content);
    }

    public function isSafePath(string $relativePath): bool
    {
        $realBase = realpath(base_path());
        $realTarget = realpath(dirname(base_path($relativePath))) ?: dirname(base_path($relativePath));

        return str_starts_with($realTarget, $realBase)
            && !str_contains($relativePath, '..')
            && preg_match('/\\.(php|blade\\.php|js|ts|vue|json|yaml|yml)$/', $relativePath);
    }

}