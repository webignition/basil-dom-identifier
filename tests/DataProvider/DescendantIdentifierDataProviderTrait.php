<?php

declare(strict_types=1);

namespace webignition\BasilDomIdentifier\Tests\DataProvider;

use webignition\BasilDomIdentifier\Model\DomIdentifier;

trait DescendantIdentifierDataProviderTrait
{
    public function descendantIdentifierDataProvider(): array
    {
        return [
            'direct descendant; css parent, css child' => [
                'identifierString' => '{{ $".parent" }} $".child"',
                'expectedIdentifier' => (new DomIdentifier('.child'))
                    ->withParentIdentifier(new DomIdentifier('.parent')),
            ],
            'direct descendant; css parent, xpath child' => [
                'identifierString' => '{{ $".parent" }} $"/child"',
                'expectedIdentifier' => (new DomIdentifier('/child'))
                    ->withParentIdentifier(new DomIdentifier('.parent')),
            ],
            'direct descendant; xpath parent, css child' => [
                'identifierString' => '{{ $"/parent" }} $".child"',
                'expectedIdentifier' => (new DomIdentifier('.child'))
                    ->withParentIdentifier(new DomIdentifier('/parent')),
            ],
            'direct descendant; xpath parent, xpath child' => [
                'identifierString' => '{{ $"/parent" }} $"/child"',
                'expectedIdentifier' => (new DomIdentifier('/child'))
                    ->withParentIdentifier(new DomIdentifier('/parent')),
            ],
            'indirect descendant' => [
                'string' => '{{ {{ $".inner-parent" }} $".inner-child" }} $".child"',
                'expectedIdentifier' => (new DomIdentifier('.child'))
                    ->withParentIdentifier(
                        (new DomIdentifier('.inner-child'))
                            ->withParentIdentifier(new DomIdentifier('.inner-parent'))
                    ),
            ],
            'indirectly indirect descendant' => [
                'string' => '{{ {{ {{ $".inner-inner-parent" }} $".inner-inner-child" }} $".inner-child" }} $".child"',
                'expectedIdentifier' => (new DomIdentifier('.child'))
                    ->withParentIdentifier(
                        (new DomIdentifier('.inner-child'))
                            ->withParentIdentifier(
                                (new DomIdentifier('.inner-inner-child'))
                                    ->withParentIdentifier(new DomIdentifier('.inner-inner-parent'))
                            )
                    ),
            ],
        ];
    }
}
