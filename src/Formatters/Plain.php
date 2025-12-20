<?php

declare(strict_types=1);

namespace Differ\Differ\Formatters;

function formatDiffPlain(array $diff): string
{
    $formatDiffPlain = function (array $diff, string ...$keys) use (&$formatDiffPlain): array {
        $lines = [];
        foreach ($diff as $key => $d) {
            if (\array_key_exists(' ', $d)) {
                if (\is_array($d[' '])) {
                    array_push($lines, ...$formatDiffPlain($d[' '], ...[...$keys, $key]));
                }
                continue;
            }
            $key = implode('.', [...$keys, $key]);
            $lines[] = "Property '$key' was " . match (
                (\array_key_exists('-', $d) ? 1 : 0) + (\array_key_exists('+', $d) ? 2 : 0)
            ) {
                1 => 'removed',
                2 => 'added with value: ' . formatValue($d['+']),
                3 => 'updated. From ' . formatValue($d['-']) . ' to ' . formatValue($d['+']),
                default => die('Impossible')
            };
        }
        return $lines;
    };
    return implode("\n", $formatDiffPlain($diff));
}

function formatValue(null|string|int|float|bool|array $value): string
{
    return null === $value ? 'null' : (\is_array($value) ? '[complex value]' : var_export($value, true));
}
