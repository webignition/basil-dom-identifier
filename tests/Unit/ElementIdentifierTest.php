<?php

declare(strict_types=1);

namespace webignition\DomElementIdentifier\Tests\Unit;

use webignition\DomElementIdentifier\ElementIdentifier;
use webignition\DomElementIdentifier\ElementIdentifierInterface;

class ElementIdentifierTest extends \PHPUnit\Framework\TestCase
{
    public function testParentIdentifier()
    {
        $domIdentifier = new ElementIdentifier('.selector');
        $this->assertNull($domIdentifier->getParentIdentifier());

        $parentIdentifier = new ElementIdentifier('.parent');
        $domIdentifier = $domIdentifier->withParentIdentifier($parentIdentifier);

        $this->assertSame($parentIdentifier, $domIdentifier->getParentIdentifier());
    }

    /**
     * @dataProvider toStringDataProvider
     */
    public function testToString(ElementIdentifierInterface $domIdentifier, string $expectedString)
    {
        $this->assertSame($expectedString, (string) $domIdentifier);
    }

    public function toStringDataProvider(): array
    {
        return [
            'empty' => [
                'domIdentifier' => new ElementIdentifier(''),
                'expectedString' => '$""',
            ],
            'css selector' => [
                'locator' => new ElementIdentifier('.selector'),
                'expectedString' => '$".selector"',
            ],
            'css selector containing double quotes' => [
                'locator' => new ElementIdentifier('a[href="https://example.org"]'),
                'expectedString' => '$"a[href=\"https://example.org\"]"',
            ],
            'xpath expression' => [
                'locator' => new ElementIdentifier('//body'),
                'expectedString' => '$"//body"',
            ],
            'xpath expression containing double quotes' => [
                'locator' => new ElementIdentifier('//*[@id="id"]'),
                'expectedString' => '$"//*[@id=\"id\"]"',
            ],
            'css selector with ordinal position' => [
                'locator' => new ElementIdentifier('.selector', 3),
                'expectedString' => '$".selector":3',
            ],
            'css selector with parent' => [
                'locator' => (new ElementIdentifier('.selector'))
                    ->withParentIdentifier(
                        new ElementIdentifier('.parent')
                    ),
                'expectedString' => '{{ $".parent" }} $".selector"',
            ],
        ];
    }
}
