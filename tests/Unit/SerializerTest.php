<?php

declare(strict_types=1);

namespace webignition\DomElementIdentifier\Tests\Unit;

use webignition\DomElementIdentifier\AttributeIdentifier;
use webignition\DomElementIdentifier\ElementIdentifier;
use webignition\DomElementIdentifier\ElementIdentifierInterface;
use webignition\DomElementIdentifier\InvalidJsonException;

class SerializerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider toArrayDataProvider
     *
     * @param ElementIdentifierInterface $elementIdentifier
     * @param array<mixed> $expectedData
     */
    public function testToArray(ElementIdentifierInterface $elementIdentifier, array $expectedData)
    {
        $this->assertSame($expectedData, $elementIdentifier->jsonSerialize());
    }

    public function toArrayDataProvider(): array
    {
        return [
            'empty' => [
                'elementIdentifier' => new ElementIdentifier(''),
                'expectedData' => [
                    'parent' => null,
                    'selector' => '',
                    'position' => null,
                ],
            ],
            'element selector' => [
                'elementIdentifier' => new ElementIdentifier('.selector'),
                'expectedData' => [
                    'parent' => null,
                    'selector' => '.selector',
                    'position' => null,
                ],
            ],
            'element selector with ordinal position' => [
                'elementIdentifier' => new ElementIdentifier('.selector', 3),
                'expectedData' => [
                    'parent' => null,
                    'selector' => '.selector',
                    'position' => 3,
                ],
            ],
            'attribute selector' => [
                'elementIdentifier' => new AttributeIdentifier('.selector', 'attribute_name'),
                'expectedData' => [
                    'parent' => null,
                    'selector' => '.selector',
                    'position' => null,
                    'attribute' => 'attribute_name',
                ],
            ],
            'attribute selector with ordinal position' => [
                'elementIdentifier' => new AttributeIdentifier('.selector', 'attribute_name', 3),
                'expectedData' => [
                    'parent' => null,
                    'selector' => '.selector',
                    'position' => 3,
                    'attribute' => 'attribute_name',
                ],
            ],
            'parent > child' => [
                'elementIdentifier' => (new ElementIdentifier('.child'))
                    ->withParentIdentifier(
                        new ElementIdentifier('.parent')
                    ),
                'expectedData' => [
                    'parent' => [
                        'parent' => null,
                        'selector' => '.parent',
                        'position' => null,
                    ],
                    'selector' => '.child',
                    'position' => null,
                ],
            ],
            'grandparent > parent > child' => [
                'elementIdentifier' => (new ElementIdentifier('.child'))
                    ->withParentIdentifier(
                        (new ElementIdentifier('.parent'))
                            ->withParentIdentifier(
                                new ElementIdentifier('.grandparent')
                            )
                    ),
                'expectedData' => [
                    'parent' => [
                        'parent' => [
                            'parent' => null,
                            'selector' => '.grandparent',
                            'position' => null,
                        ],
                        'selector' => '.parent',
                        'position' => null,
                    ],
                    'selector' => '.child',
                    'position' => null,
                ],
            ],
        ];
    }

    /**
     * @dataProvider deserializeFromJsonDataProvider
     */
    public function testDeserializeFromJsonSuccess(string $json, ElementIdentifierInterface $expectedIdentifier)
    {
        $this->assertEquals($expectedIdentifier, ElementIdentifier::fromJson($json));
    }

    public function deserializeFromJsonDataProvider(): array
    {
        return [
            'element selector, no parents' => [
                'json' => json_encode([
                    'selector' => '.selector',
                ]),
                'expectedIdentifier' => new ElementIdentifier('.selector'),
            ],
            'element selector, has position, no parents' => [
                'json' => json_encode([
                    'selector' => '.selector',
                    'position' => 7,
                ]),
                'expectedIdentifier' => new ElementIdentifier('.selector', 7),
            ],
            'attribute selector, no parents' => [
                'json' => json_encode([
                    'selector' => '.selector',
                    'attribute' => 'attribute_name',
                ]),
                'expectedIdentifier' => new AttributeIdentifier('.selector', 'attribute_name'),
            ],
            'attribute selector, has position, no parents' => [
                'json' => json_encode([
                    'selector' => '.selector',
                    'position' => 5,
                    'attribute' => 'attribute_name',
                ]),
                'expectedIdentifier' => new AttributeIdentifier('.selector', 'attribute_name', 5),
            ],
            'parent > child' => [
                'json' => json_encode([
                    'parent' => [
                        'selector' => '.parent'
                    ],
                    'selector' => '.child',
                ]),
                'expectedIdentifier' => (new ElementIdentifier('.child'))
                    ->withParentIdentifier(
                        new ElementIdentifier('.parent')
                    ),
            ],
            'grandparent > parent > child' => [
                'json' => json_encode([
                    'parent' => [
                        'parent' => [
                            'selector' => '.grandparent',
                        ],
                        'selector' => '.parent'
                    ],
                    'selector' => '.child',
                ]),
                'expectedIdentifier' => (new ElementIdentifier('.child'))
                    ->withParentIdentifier(
                        (new ElementIdentifier('.parent'))
                            ->withParentIdentifier(
                                new ElementIdentifier('.grandparent')
                            )
                    ),
            ],
        ];
    }

    /**
     * @dataProvider deserializeFromJsonReturnsNullDataProvider
     */
    public function testDeserializeFromJsonReturnsNull(string $json)
    {
        $this->expectExceptionObject(new InvalidJsonException($json));

        ElementIdentifier::fromJson($json);
    }

    public function deserializeFromJsonReturnsNullDataProvider(): array
    {
        return [
            'data is not an array' => [
                'json' => json_encode('string'),
            ],
            'position is not an integer' => [
                'json' => json_encode([
                    'selector' => '.selector',
                    'position' => 'string',
                ]),
            ],
        ];
    }
}
