<?php

declare(strict_types=1);

namespace Differ\Differ\Formatters;

function formatDiffJson(array $diff): string
{
    $json = json_encode($diff);
    assert(\is_string($json));
    return $json;
}
