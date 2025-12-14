<?php

declare(strict_types=1);

namespace Differ\Differ\Tests;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

#[CoversFunction('Differ\Differ\genDiff')]
class GetDiffTest extends TestCase
{
    private const string RESULT_DIFF = <<<'DIFF'
{
  - follow: false
    host: hexlet.io
  - proxy: 123.234.53.22
  - timeout: 50
  + timeout: 20
  + verbose: true
}
DIFF;

    public function testGetDiffJSON(): void
    {
        $this->assertSame(self::RESULT_DIFF, genDiff('tests/fixtures/file1.json', 'tests/fixtures/file2.json'));
    }

    public function testGetDiffYAML(): void
    {
        $this->assertSame(self::RESULT_DIFF, genDiff('tests/fixtures/file1.yaml', 'tests/fixtures/file2.yml'));
    }

    public function testGetDiffInvalidType(): void
    {
        $this->expectException(\RuntimeException::class);
        genDiff('tests/fixtures/file1.xml', 'tests/fixtures/file2.xml');
    }
}
