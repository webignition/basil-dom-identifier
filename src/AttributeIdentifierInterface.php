<?php

declare(strict_types=1);

namespace webignition\DomElementIdentifier;

interface AttributeIdentifierInterface extends ElementIdentifierInterface
{
    public function getAttributeName(): string;
}
