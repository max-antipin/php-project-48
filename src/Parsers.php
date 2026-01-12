<?php

declare(strict_types=1);

namespace Differ\Differ;

use Symfony\Component\Yaml\Yaml;

function getContentParser(string $filename): callable
{
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $check = static function (mixed $data) use ($filename): object {
        if (!\is_object($data)) {
            throw new \RuntimeException("Invalid file format '$filename'");
        }
        return $data;
    };
    return match ($ext) {
        'json' => static fn (string $s): object => $check(json_decode($s, false)),
        'yaml', 'yml' => static fn (string $s): object => $check(Yaml::parse($s, Yaml::PARSE_OBJECT_FOR_MAP)),
        default => throw new \RuntimeException("Unsupported file type '$ext'")
    };
}
