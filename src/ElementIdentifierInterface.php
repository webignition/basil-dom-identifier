<?php

declare(strict_types=1);

namespace webignition\DomElementIdentifier;

use webignition\DomElementLocator\ElementLocatorInterface;

interface ElementIdentifierInterface extends ElementLocatorInterface
{
    public function getParentIdentifier(): ?ElementIdentifierInterface;
    public function withParentIdentifier(ElementIdentifierInterface $parentIdentifier): ElementIdentifierInterface;

    public function __toString(): string;
}
