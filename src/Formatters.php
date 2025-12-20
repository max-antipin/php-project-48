<?php

declare(strict_types=1);

namespace Differ\Differ;

use Differ\Differ\Enum\Format;

function getFormatter(string $format): callable
{
    return __NAMESPACE__ . '\\Formatters\\' . match ($format) {
        Format::STYLISH->value => 'formatDiffStylish',
        Format::PLAIN->value => 'formatDiffPlain',
        Format::JSON->value => 'formatDiffJson',
        default => throw new \UnexpectedValueException(
            "Invalid format name: $format; acceptable names: " . implode(', ', array_column(Format::cases(), 'value'))
        )
    };
}
