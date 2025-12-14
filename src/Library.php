<?php

declare(strict_types=1);

namespace Differ\Differ;

function decodeFile(string $filename): array
{
    return json_decode(file_get_contents($filename), true);
}

function genDiff(string $filename1, string $filename2): string
{
    $formatLine = static fn(
            string $sign,
            string $key,
            string|int|float|bool $value
        ): string => "$sign $key: " . (\is_string($value) ? $value : var_export($value, true));
    $file1 = decodeFile($filename1);
    $file2 = decodeFile($filename2);
    $allKeys = array_keys([...$file1, ...$file2]);
    sort($allKeys, SORT_STRING);
    $lines = [];
    foreach ($allKeys as $key) {
        switch ((isset($file1[$key]) ? 1 : 0) + (isset($file2[$key]) ? 2 : 0)) {
            case 1:
                $lines[] = $formatLine('-', $key, $file1[$key]);
                break;
            case 2:
                $lines[] = $formatLine('+', $key, $file2[$key]);
                break;
            case 3:
                if ($file1[$key] === $file2[$key]) {
                    $lines[] = $formatLine(' ', $key, $file1[$key]);
                } else {
                    $lines[] = $formatLine('-', $key, $file1[$key]);
                    $lines[] = $formatLine('+', $key, $file2[$key]);
                }
                break;
            default:
                die('Impossible');
        }
    }
    $diff = implode("\n", array_map(static fn (string $s): string => "  $s", $lines));
    return "{\n$diff\n}";
}
