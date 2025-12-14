<?php

declare(strict_types=1);

namespace Differ\Differ\Tests;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

#[CoversFunction('Differ\Differ\genDiff')]
class GetDiffTest extends TestCase
{
    public function testGetDiffJSON(): void
    {
        $resultDiff = <<<'DIFF'
{
  - follow: false
    host: hexlet.io
  - proxy: 123.234.53.22
  - timeout: 50
  + timeout: 20
  + verbose: true
}
DIFF;
        $this->assertSame($resultDiff, genDiff('tests/fixtures/file1.json', 'tests/fixtures/file2.json'));
    }
}
