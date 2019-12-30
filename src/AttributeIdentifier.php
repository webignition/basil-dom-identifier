<?php

declare(strict_types=1);

namespace webignition\DomElementIdentifier;

use webignition\DomElementLocator\ElementLocator;

class AttributeIdentifier extends ElementIdentifier implements AttributeIdentifierInterface
{
    /**
     * @var string|null
     */
    private $attributeName = null;

    public function getAttributeName(): ?string
    {
        return $this->attributeName;
    }

    public function withAttributeName(string $attributeName): ElementIdentifierInterface
    {
        $new = clone $this;
        $new->attributeName = $attributeName;

        return $new;
    }

    public function __toString(): string
    {
        $string = parent::__toString();

        if (null !== $this->attributeName) {
            $string .= '.' . $this->attributeName;
        }

        return $string;
    }
}
