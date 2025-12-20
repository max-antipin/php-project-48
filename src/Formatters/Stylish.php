<?php

declare(strict_types=1);

namespace Differ\Differ\Formatters;

function formatDiffStylish(array $diff, int $level = 0): string
{
    $lines = [];
    foreach ($diff as $key => $d) {
        foreach ($d as $sign => $value) {
            $lines[] = formatLineStylish(
                $sign,
                $key,
                \is_array($value) ? formatDiffStylish($value, $level + 1) : $value
            );
        }
    }
    $offset = str_repeat(' ', 4 * $level);
    return implode("\n", ['{', ...array_map(static fn (string $s): string => "$offset  $s", $lines), "$offset}"]);
}

function formatLineStylish(
    string $sign,
    string $key,
    null|string|int|float|bool $value
): string {
    return "$sign $key: " . (null === $value ? 'null' : (\is_string($value) ? $value : var_export($value, true)));
}
