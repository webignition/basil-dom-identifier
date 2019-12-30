<?php

declare(strict_types=1);

namespace webignition\DomElementIdentifier\Tests\Unit;

use webignition\DomElementIdentifier\AttributeIdentifier;
use webignition\DomElementIdentifier\ElementIdentifier;
use webignition\DomElementIdentifier\ElementIdentifierInterface;
use webignition\DomElementIdentifier\InvalidJsonException;
use webignition\DomElementIdentifier\Serializer;

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
        $this->assertSame($expectedData, Serializer::toArray($elementIdentifier));
    }

    public function toArrayDataProvider(): array
    {
        return [
            'empty' => [
                'elementIdentifier' => new ElementIdentifier(''),
                'expectedData' => [
                    Serializer::KEY_PARENT => null,
                    Serializer::KEY_LOCATOR => '',
                    Serializer::KEY_POSITION => null,
                ],
            ],
            'element selector' => [
                'elementIdentifier' => new ElementIdentifier('.selector'),
                'expectedData' => [
                    Serializer::KEY_PARENT => null,
                    Serializer::KEY_LOCATOR => '.selector',
                    Serializer::KEY_POSITION => null,
                ],
            ],
            'element selector with ordinal position' => [
                'elementIdentifier' => new ElementIdentifier('.selector', 3),
                'expectedData' => [
                    Serializer::KEY_PARENT => null,
                    Serializer::KEY_LOCATOR => '.selector',
                    Serializer::KEY_POSITION => 3,
                ],
            ],
            'attribute selector' => [
                'elementIdentifier' => new AttributeIdentifier('.selector', 'attribute_name'),
                'expectedData' => [
                    Serializer::KEY_PARENT => null,
                    Serializer::KEY_LOCATOR => '.selector',
                    Serializer::KEY_POSITION => null,
                    Serializer::KEY_ATTRIBUTE => 'attribute_name',
                ],
            ],
            'attribute selector with ordinal position' => [
                'elementIdentifier' => new AttributeIdentifier('.selector', 'attribute_name', 3),
                'expectedData' => [
                    Serializer::KEY_PARENT => null,
                    Serializer::KEY_LOCATOR => '.selector',
                    Serializer::KEY_POSITION => 3,
                    Serializer::KEY_ATTRIBUTE => 'attribute_name',
                ],
            ],
            'parent > child' => [
                'elementIdentifier' => (new ElementIdentifier('.child'))
                    ->withParentIdentifier(
                        new ElementIdentifier('.parent')
                    ),
                'expectedData' => [
                    Serializer::KEY_PARENT => [
                        Serializer::KEY_PARENT => null,
                        Serializer::KEY_LOCATOR => '.parent',
                        Serializer::KEY_POSITION => null,
                    ],
                    Serializer::KEY_LOCATOR => '.child',
                    Serializer::KEY_POSITION => null,
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
                    Serializer::KEY_PARENT => [
                        Serializer::KEY_PARENT => [
                            Serializer::KEY_PARENT => null,
                            Serializer::KEY_LOCATOR => '.grandparent',
                            Serializer::KEY_POSITION => null,
                        ],
                        Serializer::KEY_LOCATOR => '.parent',
                        Serializer::KEY_POSITION => null,
                    ],
                    Serializer::KEY_LOCATOR => '.child',
                    Serializer::KEY_POSITION => null,
                ],
            ],
        ];
    }

    /**
     * @dataProvider deserializeFromJsonDataProvider
     */
    public function testDeserializeFromJsonSuccess(string $json, ElementIdentifierInterface $expectedIdentifier)
    {
        $this->assertEquals($expectedIdentifier, Serializer::fromJson($json));
    }

    public function deserializeFromJsonDataProvider(): array
    {
        return [
            'element selector, no parents' => [
                'json' => json_encode([
                    Serializer::KEY_LOCATOR => '.selector',
                ]),
                'expectedIdentifier' => new ElementIdentifier('.selector'),
            ],
            'element selector, has position, no parents' => [
                'json' => json_encode([
                    Serializer::KEY_LOCATOR => '.selector',
                    Serializer::KEY_POSITION => 7,
                ]),
                'expectedIdentifier' => new ElementIdentifier('.selector', 7),
            ],
            'attribute selector, no parents' => [
                'json' => json_encode([
                    Serializer::KEY_LOCATOR => '.selector',
                    Serializer::KEY_ATTRIBUTE => 'attribute_name',
                ]),
                'expectedIdentifier' => new AttributeIdentifier('.selector', 'attribute_name'),
            ],
            'attribute selector, has position, no parents' => [
                'json' => json_encode([
                    Serializer::KEY_LOCATOR => '.selector',
                    Serializer::KEY_POSITION => 5,
                    Serializer::KEY_ATTRIBUTE => 'attribute_name',
                ]),
                'expectedIdentifier' => new AttributeIdentifier('.selector', 'attribute_name', 5),
            ],
            'parent > child' => [
                'json' => json_encode([
                    Serializer::KEY_PARENT => [
                        Serializer::KEY_LOCATOR => '.parent'
                    ],
                    Serializer::KEY_LOCATOR => '.child',
                ]),
                'expectedIdentifier' => (new ElementIdentifier('.child'))
                    ->withParentIdentifier(
                        new ElementIdentifier('.parent')
                    ),
            ],
            'grandparent > parent > child' => [
                'json' => json_encode([
                    Serializer::KEY_PARENT => [
                        Serializer::KEY_PARENT => [
                            Serializer::KEY_LOCATOR => '.grandparent',
                        ],
                        Serializer::KEY_LOCATOR => '.parent'
                    ],
                    Serializer::KEY_LOCATOR => '.child',
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
                    Serializer::KEY_LOCATOR => '.selector',
                    Serializer::KEY_POSITION => 'string',
                ]),
            ],
        ];
    }
}
