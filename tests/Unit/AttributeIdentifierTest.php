<?php

declare(strict_types=1);

namespace webignition\DomElementIdentifier\Tests\Unit;

use webignition\DomElementIdentifier\AttributeIdentifier;
use webignition\DomElementIdentifier\AttributeIdentifierInterface;
use webignition\DomElementIdentifier\ElementIdentifier;

class AttributeIdentifierTest extends \PHPUnit\Framework\TestCase
{
    public function testAttributeName()
    {
        $identifier = new AttributeIdentifier('.selector');
        $this->assertNull($identifier->getAttributeName());

        $attributeName = 'attribute_name';
        $identifier = $identifier->withAttributeName($attributeName);

        if ($identifier instanceof AttributeIdentifierInterface) {
            $this->assertSame($attributeName, $identifier->getAttributeName());
        }
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
                'locator' => (new AttributeIdentifier('.selector'))
                    ->withAttributeName('attribute_name'),
                'expectedString' => '$".selector".attribute_name',
            ],
            'css selector with parent, ordinal position and attribute name' => [
                'locator' => (new AttributeIdentifier('.selector', 7))
                    ->withAttributeName('attribute_name')
                    ->withParentIdentifier(
                        new ElementIdentifier('.parent')
                    ),
                'expectedString' => '{{ $".parent" }} $".selector":7.attribute_name',
            ],
        ];
    }
}
