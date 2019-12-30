<?php

declare(strict_types=1);

namespace webignition\DomElementIdentifier;

class InvalidJsonException extends \Exception
{
    private $json;

    public function __construct(string $json)
    {
        parent::__construct('Invalid json: "' . $json . '"');

        $this->json = $json;
    }

    public function getJson(): string
    {
        return $this->json;
    }
}
