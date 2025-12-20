<?php

declare(strict_types=1);

namespace Differ\Differ\Formatters;

function formatDiffJson(array $diff): string
{
    return json_encode($diff);
}
