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

    public function testJsonSerialize()
    {
        $this->assertSame(
            [
                'parent' => null,
                'selector' => '.selector',
                'position' => null,
                'attribute' => 'attribute_name',
            ],
            (new AttributeIdentifier('.selector', 'attribute_name'))->jsonSerialize()
        );
    }

    public function testDeserializeFromJson()
    {
        $this->assertEquals(
            new AttributeIdentifier('.selector', 'attribute_name'),
            ElementIdentifier::fromJson(json_encode([
                'selector' => '.selector',
                'attribute' => 'attribute_name',
            ]))
        );
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
