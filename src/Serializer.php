<?php

declare(strict_types=1);

namespace webignition\DomElementIdentifier;

use webignition\DomElementLocator\ElementLocator;

class Serializer
{
    public static function toArray(ElementIdentifierInterface $elementIdentifier)
    {
        $parentIdentifier = $elementIdentifier->getParentIdentifier();

        $serializedParent = $parentIdentifier instanceof ElementIdentifierInterface
            ? self::toArray($parentIdentifier)
            : null;

        $data = [
            'parent' => $serializedParent,
            'selector' => $elementIdentifier->getLocator(),
            'position' => $elementIdentifier->getOrdinalPosition(),
        ];

        if ($elementIdentifier instanceof AttributeIdentifierInterface) {
            $data['attribute'] = $elementIdentifier->getAttributeName();
        }

        return $data;
    }
}
