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

    /**
     * @param string $json
     *
     * @throws InvalidJsonException
     *
     * @return ElementIdentifierInterface
     */
    public static function fromJson(string $json): ElementIdentifierInterface;

    public static function fromAttributeIdentifier(ElementIdentifierInterface $identifier): ElementIdentifierInterface;

    public function __toString(): string;
}
