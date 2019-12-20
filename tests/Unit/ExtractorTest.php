<?php

declare(strict_types=1);

namespace webignition\BasilDomIdentifier\Tests\Unit;

use webignition\BasilDomIdentifier\Extractor;
use webignition\BasilDomIdentifier\Tests\DataProvider\IdentifierStringDataProviderTrait;

class ExtractorTest extends \PHPUnit\Framework\TestCase
{
    use IdentifierStringDataProviderTrait;

    /**
     * @var Extractor
     */
    private $extractor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->extractor = new Extractor();
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
