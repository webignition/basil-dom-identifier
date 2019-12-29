<?php

declare(strict_types=1);

namespace webignition\BasilDomIdentifier\Extractor;

class Extractor
{
    private $pageElementIdentifierExtractor;
    private $descendantExtractor;

    public function __construct(
        PageElementIdentifierExtractor $pageElementIdentifierExtractor,
        DescendantExtractor $descendantExtractor
    ) {
        $this->pageElementIdentifierExtractor = $pageElementIdentifierExtractor;
        $this->descendantExtractor = $descendantExtractor;
    }

    public static function createExtractor(): Extractor
    {
        return new Extractor(
            PageElementIdentifierExtractor::createExtractor(),
            DescendantExtractor::createExtractor()
        );
    }

    public function extract(string $string): ?string
    {
        $descendantIdentifierString = $this->descendantExtractor->extract($string);
        if (null !== $descendantIdentifierString) {
            return $descendantIdentifierString;
        }

        return $this->pageElementIdentifierExtractor->extractIdentifierString($string);
    }
}
