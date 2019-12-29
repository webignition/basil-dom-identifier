<?php

declare(strict_types=1);

namespace webignition\BasilDomIdentifier\Tests\Unit\Extractor;

use webignition\BasilDomIdentifier\Extractor\PageElementIdentifierExtractor;
use webignition\BasilDomIdentifier\Tests\DataProvider\IdentifierStringDataProviderTrait;

class PageElementIdentifierExtractorTest extends \PHPUnit\Framework\TestCase
{
    use IdentifierStringDataProviderTrait;

    /**
     * @var PageElementIdentifierExtractor
     */
    private $extractor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->extractor = new PageElementIdentifierExtractor();
    }

    /**
     * @dataProvider unhandledStringsDataProvider
     */
    public function testExtractIdentifierStringReturnsEmptyValue(string $string)
    {
        $this->assertNull($this->extractor->extractIdentifierString($string));
    }

    public function unhandledStringsDataProvider(): array
    {
        return [
            'empty' => [
                'string' => '',
            ],
            'not internally quoted' => [
                'string' => '$value',
            ],
        ];
    }

    /**
     * @dataProvider identifierStringDataProvider
     */
    public function testExtractIdentifierStringReturnsString(string $string, string $expectedIdentifierString)
    {
        $identifierString = $this->extractor->extractIdentifierString($string);

        $this->assertSame($expectedIdentifierString, $identifierString);
    }
}
