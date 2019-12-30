<?php

declare(strict_types=1);

namespace webignition\DomElementIdentifier;

use webignition\DomElementLocator\ElementLocatorInterface;

interface AttributeIdentifierInterface extends ElementIdentifierInterface
{
    public function getAttributeName(): ?string;
    public function withAttributeName(string $attributeName): ElementIdentifierInterface;
}
