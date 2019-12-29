<?php

declare(strict_types=1);

namespace webignition\BasilDomIdentifier\Tests\Unit;

use webignition\BasilDomIdentifier\Factory;
use webignition\BasilDomIdentifier\Model\DomIdentifier;
use webignition\BasilDomIdentifier\Tests\DataProvider\AttributeIdentifierDataProviderTrait;
use webignition\BasilDomIdentifier\Tests\DataProvider\CssSelectorIdentifierDataProviderTrait;
use webignition\BasilDomIdentifier\Tests\DataProvider\UnknownIdentifierDataProviderTrait;
use webignition\BasilDomIdentifier\Tests\DataProvider\XpathExpressionIdentifierDataProviderTrait;

class FactoryTest extends \PHPUnit\Framework\TestCase
{
    use AttributeIdentifierDataProviderTrait;
    use CssSelectorIdentifierDataProviderTrait;
    use XpathExpressionIdentifierDataProviderTrait;
    use UnknownIdentifierDataProviderTrait;

    /**
     * @var Factory
     */
    private $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = Factory::createFactory();
    }

    /**
     * @dataProvider attributeIdentifierDataProvider
     * @dataProvider cssSelectorIdentifierDataProvider
     * @dataProvider xpathExpressionIdentifierDataProvider
     */
    public function testCreateFromIdentifierStringSuccess(string $identifierString, DomIdentifier $expectedIdentifier)
    {
        $identifier = $this->factory->createFromIdentifierString($identifierString);

        $this->assertInstanceOf(DomIdentifier::class, $identifier);
        $this->assertEquals($expectedIdentifier, $identifier);
    }

    /**
     * @dataProvider unknownIdentifierStringDataProvider
     */
    public function testCreateFromIdentifierStringReturnsNull(string $identifierString)
    {
        $this->assertNull($this->factory->createFromIdentifierString($identifierString));
    }
}
