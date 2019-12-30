<?php

declare(strict_types=1);

namespace webignition\DomElementIdentifier;

use webignition\DomElementLocator\ElementLocator;

class ElementIdentifier extends ElementLocator implements ElementIdentifierInterface
{
    /**
     * @var ElementIdentifierInterface
     */
    private $parentIdentifier;

    public function getParentIdentifier(): ?ElementIdentifierInterface
    {
        return $this->parentIdentifier;
    }

    public function withParentIdentifier(ElementIdentifierInterface $parentIdentifier): ElementIdentifierInterface
    {
        $new = clone $this;
        $new->parentIdentifier = $parentIdentifier;

        return $new;
    }

    public function __toString(): string
    {
        $string = '$' . parent::__toString();

        if (null !== $this->parentIdentifier) {
            $string = '{{ ' . (string) $this->parentIdentifier . ' }} ' . $string;
        }

        return $string;
    }
}
