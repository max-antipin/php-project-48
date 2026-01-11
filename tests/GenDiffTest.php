<?php

declare(strict_types=1);

namespace Differ\Differ\Tests;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

#[CoversFunction('Differ\Differ\genDiff')]
class GenDiffTest extends TestCase
{
    public static function dataProviderGetDiff(): array
    {
        return [
            'json stylish' => ['file1.json', 'file2.json', 'stylish'],
            'yaml stylish' => ['file1.yaml', 'file2.yml', 'stylish'],
            'json plain' => ['file1.json', 'file2.json', 'plain'],
            'yaml plain' => ['file1.yaml', 'file2.yml', 'plain'],
        ];
    }

    #[DataProvider('dataProviderGetDiff')]
    public function testGetDiff(string $file1, string $file2, string $format): void
    {
        $this->assertStringEqualsFile(
            $this->fixtureName("diff-$format.txt"),
            genDiff($this->fixtureName($file1), $this->fixtureName($file2), $format)
        );
    }

    public static function dataProviderGetDiffInvalid(): array
    {
        return [
            'type 1' => [\RuntimeException::class, 'file1.xml', 'file2.json'],
            'type 2' => [\RuntimeException::class, 'file1.json', 'file2.xml'],
            'format 1' => [\RuntimeException::class, 'file-invalid.json', 'file2.json'],
            'format 2' => [\RuntimeException::class, 'file1.json', 'file-invalid.json'],
        ];
    }

    #[DataProvider('dataProviderGetDiffInvalid')]
    public function testGetDiffInvalid(string $exception, string $file1, string $file2): void
    {
        /** @var class-string<\Throwable> $exception */
        $this->expectException($exception);
        genDiff($this->fixtureName($file1), $this->fixtureName($file2));
    }

    public function testGetDiffInvalidFormatName(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        genDiff($this->fixtureName('file1.json'), $this->fixtureName('file2.json'), 'format');
    }

    public function testGetDiffJsonJson(): void
    {
        $this->assertJson(
            genDiff($this->fixtureName('file1.json'), $this->fixtureName('file2.json'), 'json')
        );
    }

    private function fixtureName(string $name): string
    {
        return __DIR__ . "/fixtures/$name";
    }
}
