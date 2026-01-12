<?php

declare(strict_types=1);

namespace Differ\Differ;

use Differ\Differ\Enum\Format;
use Differ\Differ\Formatters;

function getFormatter(string $format): callable
{
    return match ($format) {
        Format::STYLISH->value => Formatters\formatDiffStylish(...),
        Format::PLAIN->value => Formatters\formatDiffPlain(...),
        Format::JSON->value => Formatters\formatDiffJson(...),
        default => throw new \UnexpectedValueException(
            "Invalid format name: $format; acceptable names: " . implode(', ', array_column(Format::cases(), 'value'))
        )
    };
}
