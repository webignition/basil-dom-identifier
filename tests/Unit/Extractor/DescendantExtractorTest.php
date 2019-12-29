<?php

declare(strict_types=1);

namespace webignition\BasilDomIdentifier\Tests\Unit\Extractor;

use webignition\BasilDomIdentifier\Extractor\DescendantExtractor;
use webignition\BasilDomIdentifier\Tests\DataProvider\DescendantIdentifierStringDataProviderTrait;

class DescendantExtractorTest extends \PHPUnit\Framework\TestCase
{
    use DescendantIdentifierStringDataProviderTrait;

    /**
     * @var DescendantExtractor
     */
    private $extractor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->extractor = DescendantExtractor::createExtractor();
    }

    /**
     * @dataProvider returnsEmptyValueDataProvider
     */
    public function testExtractReturnsEmptyValue(string $string)
    {
        $this->assertNull($this->extractor->extract($string));
    }

    public function returnsEmptyValueDataProvider(): array
    {
        return [
            'empty' => [
                'string' => '',
            ],
            'variable value' => [
                'string' => '$elements.element_name',
            ],
            'invalid parent identifier' => [
                'string' => '{{ .parent }} $".child"',
            ],
            'invalid child identifier' => [
                'string' => '{{ $".parent" }} .child',
            ],
            'lacking parent suffix' => [
                'string' => '{{ $".parent" .child',
            ],
            'parent prefix only' => [
                'string' => '{{ ',
            ],
        ];
    }

    /**
     * @dataProvider descendantIdentifierStringDataProvider
     */
    public function testExtractReturnsString(string $string, string $expectedIdentifierString)
    {
        $identifierString = $this->extractor->extract($string);

        $this->assertSame($expectedIdentifierString, $identifierString);
    }

    /**
     * @dataProvider extractParentIdentifierReturnsEmptyValueDataProvider
     */
    public function testExtractParentIdentifierReturnsEmptyValue(string $string)
    {
        $this->assertNull($this->extractor->extractParentIdentifier($string));
    }

    public function extractParentIdentifierReturnsEmptyValueDataProvider(): array
    {
        return [
            'empty' => [
                'string' => '',
            ],
            'variable value' => [
                'string' => '$elements.element_name',
            ],
            'invalid parent identifier' => [
                'string' => '{{ .parent }} $".child"',
            ],
            'lacking parent suffix' => [
                'string' => '{{ $".parent" .child',
            ],
            'parent prefix only' => [
                'string' => '{{ ',
            ],
        ];
    }

    /**
     * @dataProvider extractParentIdentifierDataProvider
     */
    public function testExtractParentIdentifierReturnsString(string $string, string $expectedIdentifierString)
    {
        $identifierString = $this->extractor->extractParentIdentifier($string);

        $this->assertSame($expectedIdentifierString, $identifierString);
    }

    public function extractParentIdentifierDataProvider(): array
    {
        return [
            'direct descendant' => [
                'string' => '{{ $".parent" }} $".child"',
                'expectedParentReference' => '$".parent"',
            ],
            'indirect descendant' => [
                'string' => '{{ {{ $".inner-parent" }} $".inner-child" }} $".child"',
                'expectedParentReference' => '{{ $".inner-parent" }} $".inner-child"',
            ],
            'indirectly indirect descendant' => [
                'string' => '{{ {{ {{ $".inner-inner-parent" }} $".inner-inner-child" }} $".inner-child" }} $".child"',
                'expectedParentReference' =>
                    '{{ {{ $".inner-inner-parent" }} $".inner-inner-child" }} $".inner-child"',
            ],
        ];
    }
}
