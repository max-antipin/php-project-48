<?php

declare(strict_types=1);

namespace Differ\Differ\Tests;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

#[CoversFunction('Differ\Differ\genDiff')]
class GenDiffTest extends TestCase
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

    private const string RESULT_DIFF_PLAIN = <<<'DIFF_PLAIN'
Property 'common.follow' was added with value: false
Property 'common.setting2' was removed
Property 'common.setting3' was updated. From true to null
Property 'common.setting4' was added with value: 'blah blah'
Property 'common.setting5' was added with value: [complex value]
Property 'common.setting6.doge.wow' was updated. From '' to 'so much'
Property 'common.setting6.ops' was added with value: 'vops'
Property 'group1.baz' was updated. From 'bas' to 'bars'
Property 'group1.nest' was updated. From [complex value] to 'str'
Property 'group2' was removed
Property 'group3' was added with value: [complex value]
DIFF_PLAIN;

    public function testGetDiffJSON(): void
    {
        $this->assertSame(self::RESULT_DIFF, genDiff('tests/fixtures/file1.json', 'tests/fixtures/file2.json'));
    }

    public function testGetDiffYAML(): void
    {
        $this->assertSame(self::RESULT_DIFF, genDiff('tests/fixtures/file1.yaml', 'tests/fixtures/file2.yml'));
    }

    public function testGetDiffPlainJSON(): void
    {
        $this->assertSame(
            self::RESULT_DIFF_PLAIN,
            genDiff('tests/fixtures/file1.json', 'tests/fixtures/file2.json')
        );
    }

    public function testGetDiffPlainYAML(): void
    {
        $this->assertSame(
            self::RESULT_DIFF_PLAIN,
            genDiff('tests/fixtures/file1.yaml', 'tests/fixtures/file2.yml')
        );
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
