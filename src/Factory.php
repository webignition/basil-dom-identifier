<?php

declare(strict_types=1);

namespace webignition\BasilDomIdentifier;

use webignition\BasilDomIdentifier\Extractor\Extractor;
use webignition\BasilDomIdentifier\Model\DomIdentifier;
use webignition\BasilIdentifierAnalyser\IdentifierTypeAnalyser;
use webignition\QuotedStringValueExtractor\QuotedStringValueExtractor;

class Factory
{
    private const POSITION_FIRST = 'first';
    private const POSITION_LAST = 'last';
    private const POSITION_PATTERN = ':(-?[0-9]+|first|last)';
    private const POSITION_REGEX = '/' . self::POSITION_PATTERN . '$/';

    private const POSITION_LABEL_MAP = [
        self::POSITION_FIRST => 1,
        self::POSITION_LAST => -1,
    ];

    private $extractor;
    private $identifierTypeAnalyser;
    private $quotedStringValueExtractor;

    public function __construct(
        Extractor $extractor,
        IdentifierTypeAnalyser $identifierTypeAnalyser,
        QuotedStringValueExtractor $quotedStringValueExtractor
    ) {
        $this->extractor = $extractor;
        $this->identifierTypeAnalyser = $identifierTypeAnalyser;
        $this->quotedStringValueExtractor = $quotedStringValueExtractor;
    }

    public static function createFactory(): Factory
    {
        return new Factory(
            Extractor::createExtractor(),
            new IdentifierTypeAnalyser(),
            QuotedStringValueExtractor::createExtractor()
        );
    }

    public function createFromIdentifierString(string $identifierString): ?DomIdentifier
    {
        $identifierString = $this->extractor->extractIdentifierString(trim($identifierString));

        if (null === $identifierString || !$this->identifierTypeAnalyser->isDomIdentifier($identifierString)) {
            return null;
        }

        $elementIdentifier = $identifierString;
        $attributeName = '';

        if ($this->identifierTypeAnalyser->isAttributeIdentifier($identifierString)) {
            $attributeName = $this->findAttributeName($identifierString);
            $elementIdentifier = $this->findElementIdentifier($identifierString, $attributeName);
        }

        $position = $this->findPosition($elementIdentifier);

        $quotedElementLocatorReference = $this->findElementLocatorReference($elementIdentifier);

        $elementLocatorString = $this->quotedStringValueExtractor->getValue(
            ltrim($quotedElementLocatorReference, '$')
        );

        $identifier = new DomIdentifier($elementLocatorString, $position);

        if ('' !== $attributeName) {
            $identifier = $identifier->withAttributeName($attributeName);
        }

        return $identifier;
    }

    private function findAttributeName(string $identifierString): string
    {
        $lastDotPosition = (int) mb_strrpos($identifierString, '.');

        return mb_substr($identifierString, $lastDotPosition + 1);
    }

    private function findElementIdentifier(string $identifierString, string $attributeName): string
    {
        return mb_substr(
            $identifierString,
            0,
            mb_strlen($identifierString) - mb_strlen($attributeName) - 1
        );
    }

    private function findPosition(string $identifierString): ?int
    {
        $positionMatches = [];
        preg_match(self::POSITION_REGEX, $identifierString, $positionMatches);

        if ([] === $positionMatches) {
            return null;
        }

        $positionMatch = $positionMatches[0];
        $positionString = ltrim($positionMatch, ':');

        $mappedPosition = self::POSITION_LABEL_MAP[$positionString] ?? null;
        if (is_int($mappedPosition)) {
            return $mappedPosition;
        }

        return (int) $positionString;
    }

    private function findElementLocatorReference(string $elementIdentifier): string
    {
        $positionMatches = [];
        preg_match(self::POSITION_REGEX, $elementIdentifier, $positionMatches);

        if ([] === $positionMatches) {
            return $elementIdentifier;
        }

        $lastPositionDelimiterPosition = (int) mb_strrpos($elementIdentifier, ':');

        return mb_substr($elementIdentifier, 0, $lastPositionDelimiterPosition - 1);
    }

    /**
     * @param string $identifierString
     *
     * @return array<int, int|string|null>
     */
    public static function extract(string $identifierString): array
    {
        $positionMatches = [];

        preg_match(self::POSITION_REGEX, $identifierString, $positionMatches);

        $position = null;

        if (empty($positionMatches)) {
            $quotedValue = $identifierString;
        } else {
            $quotedValue = (string) preg_replace(self::POSITION_REGEX, '', $identifierString);

            $positionMatch = $positionMatches[0];
            $positionString = ltrim($positionMatch, ':');

            if (self::POSITION_FIRST === $positionString) {
                $position = 1;
            } elseif (self::POSITION_LAST === $positionString) {
                $position = -1;
            } else {
                $position = (int) $positionString;
            }
        }

        return [
            $quotedValue,
            $position,
        ];
    }
}
