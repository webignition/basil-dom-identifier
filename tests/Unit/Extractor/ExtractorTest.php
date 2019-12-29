<?php

declare(strict_types=1);

namespace webignition\BasilDomIdentifier\Tests\Unit\Extractor;

use webignition\BasilDomIdentifier\Extractor\Extractor;
use webignition\BasilDomIdentifier\Tests\DataProvider\DescendantIdentifierStringDataProviderTrait;
use webignition\BasilDomIdentifier\Tests\DataProvider\IdentifierStringDataProviderTrait;

class ExtractorTest extends \PHPUnit\Framework\TestCase
{
    use DescendantIdentifierStringDataProviderTrait;
    use IdentifierStringDataProviderTrait;

    /**
     * @var Extractor
     */
    private $extractor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->extractor = Extractor::createExtractor();
    }

    /**
     * @dataProvider descendantIdentifierStringDataProvider
     * @dataProvider identifierStringDataProvider
     */
    public function testExtractReturnsString(string $string, string $expectedIdentifierString)
    {
        $identifierString = $this->extractor->extract($string);

        $this->assertSame($expectedIdentifierString, $identifierString);
    }
}
