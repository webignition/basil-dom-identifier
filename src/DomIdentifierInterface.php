<?php

declare(strict_types=1);

namespace webignition\DomElementIdentifier;

use webignition\DomElementLocator\ElementLocatorInterface;

interface DomIdentifierInterface extends ElementLocatorInterface
{
    public function getParentIdentifier(): ?DomIdentifierInterface;
    public function withParentIdentifier(DomIdentifierInterface $parentIdentifier): DomIdentifierInterface;

    public function getAttributeName(): ?string;
    public function withAttributeName(string $attributeName): DomIdentifierInterface;

    public function __toString(): string;
}
