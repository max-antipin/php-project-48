<?php

declare(strict_types=1);

namespace Differ\Differ;

use Differ\Differ\Enum\Format;
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

function readFile(string $filename): string
{
    $contents = file_get_contents($filename);
    if (false === $contents) {
        throw new \RuntimeException("Unable to open file '$filename'");
    }
    return $contents;
}

function genDiff(string $filename1, string $filename2, string $format = Format::STYLISH->value): string
{
    $parser1 = getContentParser($filename1);
    $parser2 = getContentParser($filename2);
    return getFormatter($format)(
        calcDiff(
            $parser1(readFile($filename1)),
            $parser2(readFile($filename2)),
        )
    );
}

function calcDiff(object $data1, object $data2): array
{
    $allKeys = array_keys([...get_object_vars($data1), ...get_object_vars($data2)]);
    sort($allKeys, SORT_STRING);
    $diff = array_fill_keys($allKeys, []);
    array_walk($diff, static function (array &$item, string $key) use ($data1, $data2): void {
        $item = match ((property_exists($data1, $key) ? 1 : 0) + (property_exists($data2, $key) ? 2 : 0)) {
            1 => ['-' => \is_object($data1->$key) ? calcDiff($data1->$key, $data1->$key) : $data1->$key],
            2 => ['+' => \is_object($data2->$key) ? calcDiff($data2->$key, $data2->$key) : $data2->$key],
            3 => calcDiffBetweenData($data1, $data2, $key),
            default => die('Impossible')
        };
    });
    return $diff;
}

function calcDiffBetweenData(object $data1, object $data2, string $key): array
{
    $leftObj = \is_object($data1->$key);
    $rightObj = \is_object($data2->$key);
    $bothObj = $leftObj && $rightObj;
    if ($data1->$key === $data2->$key || $bothObj) {
        return [' ' => $bothObj ? calcDiff($data1->$key, $data2->$key) : $data1->$key];
    }
    return [
        '-' => $leftObj ? calcDiff($data1->$key, $rightObj ? $data2->$key : $data1->$key) : $data1->$key,
        '+' => $rightObj ? calcDiff($data2->$key, $leftObj ? $data1->$key : $data2->$key) : $data2->$key,
    ];
}
