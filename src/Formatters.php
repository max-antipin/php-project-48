<?php

declare(strict_types=1);

namespace Differ\Differ;

use Differ\Differ\Enum\Format;

function getFormatter(Format $format): callable
{
    return __NAMESPACE__ . '\\Formatters\\' . match ($format) {
        Format::STYLISH => 'formatDiffStylish',
        Format::PLAIN => 'formatDiffPlain',
    };
}
