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

    public function getScope(): array
    {
        $scope = [];

        $parentIdentifier = $this->getParentIdentifier();
        while ($parentIdentifier instanceof ElementIdentifierInterface) {
            $scope[] = $parentIdentifier;
            $parentIdentifier = $parentIdentifier->getParentIdentifier();
        }

        return array_reverse($scope);
    }

    /**
     * @return array<mixed>
     */
    public function jsonSerialize(): array
    {
        return Serializer::toArray($this);
    }

    public static function deserializeFromJson(string $json): ?ElementIdentifierInterface
    {
        $data = json_decode($json, true);

        if (!is_array($data)) {
            return null;
        }

        var_dump($data);
        exit();

        return null;
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
