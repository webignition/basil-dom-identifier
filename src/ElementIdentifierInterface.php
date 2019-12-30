<?php

declare(strict_types=1);

namespace webignition\DomElementIdentifier;

use webignition\DomElementLocator\ElementLocatorInterface;

interface ElementIdentifierInterface extends ElementLocatorInterface, \JsonSerializable
{
    public function getParentIdentifier(): ?ElementIdentifierInterface;
    public function withParentIdentifier(ElementIdentifierInterface $parentIdentifier): ElementIdentifierInterface;

    /**
     * @return array<int, ElementIdentifierInterface>
     */
    public function getScope(): array;

    public function __toString(): string;
}
