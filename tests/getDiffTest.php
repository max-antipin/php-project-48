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
    common: {
      + follow: false
        setting1: Value 1
      - setting2: 200
      - setting3: true
      + setting3: null
      + setting4: blah blah
      + setting5: {
            key5: value5
        }
        setting6: {
            doge: {
              - wow: 
              + wow: so much
            }
            key: value
          + ops: vops
        }
    }
    group1: {
      - baz: bas
      + baz: bars
        foo: bar
      - nest: {
            key: value
        }
      + nest: str
    }
  - group2: {
        abc: 12345
        deep: {
            id: 45
        }
    }
  + group3: {
        deep: {
            id: {
                number: 45
            }
        }
        fee: 100500
    }
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

    public function testGetDiffInvalidFormat(): void
    {
        $this->expectException(\RuntimeException::class);
        genDiff('tests/fixtures/file-invalid.json', 'tests/fixtures/file2.json');
    }
}
