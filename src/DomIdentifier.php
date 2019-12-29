<?php

declare(strict_types=1);

namespace webignition\DomElementIdentifier;

use webignition\DomElementLocator\ElementLocator;

class DomIdentifier extends ElementLocator implements DomIdentifierInterface
{
    /**
     * @var string|null
     */
    private $attributeName = null;

    /**
     * @var DomIdentifierInterface
     */
    private $parentIdentifier;

    public function getParentIdentifier(): ?DomIdentifierInterface
    {
        return $this->parentIdentifier;
    }

    public function withParentIdentifier(DomIdentifierInterface $parentIdentifier): DomIdentifierInterface
    {
        $new = clone $this;
        $new->parentIdentifier = $parentIdentifier;

        return $new;
    }

    public function getAttributeName(): ?string
    {
        return $this->attributeName;
    }

    public function withAttributeName(string $attributeName): DomIdentifierInterface
    {
        $new = clone $this;
        $new->attributeName = $attributeName;

        return $new;
    }

    public function __toString(): string
    {
        $string = '$' . parent::__toString();

        if (null !== $this->parentIdentifier) {
            $string = '{{ ' . (string) $this->parentIdentifier . ' }} ' . $string;
        }

        if (null !== $this->attributeName) {
            $string .= '.' . $this->attributeName;
        }

        return $string;
    }
}
