<?php

declare(strict_types=1);

namespace webignition\DomElementIdentifier\Tests\Unit;

use webignition\DomElementIdentifier\AttributeIdentifier;
use webignition\DomElementIdentifier\AttributeIdentifierInterface;
use webignition\DomElementIdentifier\ElementIdentifier;

class AttributeIdentifierTest extends \PHPUnit\Framework\TestCase
{
    public function testGetAttributeName()
    {
        $attributeName = 'attribute_name';
        $identifier = new AttributeIdentifier('.selector', $attributeName);

        $this->assertSame($attributeName, $identifier->getAttributeName());
    }

    /**
     * @dataProvider jsonSerializeDataProvider
     *
     * @param AttributeIdentifierInterface $identifier
     * @param array<mixed> $expectedData
     */
    public function testJsonSerialize(AttributeIdentifierInterface $identifier, array $expectedData)
    {
        $this->assertSame($expectedData, $identifier->jsonSerialize());
    }

    public function jsonSerializeDataProvider(): array
    {
        return [
            'css selector with attribute' => [
                'identifier' => new AttributeIdentifier('.selector', 'attribute_name'),
                'expectedData' => [
                    'parent' => null,
                    'selector' => '.selector',
                    'position' => null,
                    'attribute' => 'attribute_name',
                ],
            ],
            'css selector with parent, ordinal position and attribute name' => [
                'identifier' => (new AttributeIdentifier('.selector', 'attribute_name', 7))
                    ->withParentIdentifier(
                        new ElementIdentifier('.parent')
                    ),
                'expectedData' => [
                    'parent' => [
                        'parent' => null,
                        'selector' => '.parent',
                        'position' => null,
                    ],
                    'selector' => '.selector',
                    'position' => 7,
                    'attribute' => 'attribute_name',
                ],
            ],
        ];
    }

    /**
     * @dataProvider toStringDataProvider
     */
    public function testToString(AttributeIdentifierInterface $domIdentifier, string $expectedString)
    {
        $this->assertSame($expectedString, (string) $domIdentifier);
    }

    public function toStringDataProvider(): array
    {
        return [
            'css selector with attribute' => [
                'locator' => new AttributeIdentifier('.selector', 'attribute_name'),
                'expectedString' => '$".selector".attribute_name',
            ],
            'css selector with parent, ordinal position and attribute name' => [
                'locator' => (new AttributeIdentifier('.selector', 'attribute_name', 7))
                    ->withParentIdentifier(
                        new ElementIdentifier('.parent')
                    ),
                'expectedString' => '{{ $".parent" }} $".selector":7.attribute_name',
            ],
        ];
    }
}
