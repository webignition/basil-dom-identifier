<?php

declare(strict_types=1);

namespace webignition\BasilDomIdentifier\Tests\DataProvider;

trait DescendantIdentifierStringDataProviderTrait
{
    public function descendantIdentifierStringDataProvider(): array
    {
        $dataSets = [
            'direct descendant' => [
                'string' => '{{ $".parent" }} $".child"',
                'expectedIdentifierString' => '{{ $".parent" }} $".child"',
            ],
            'indirect descendant' => [
                'string' => '{{ {{ $".inner-parent" }} $".inner-child" }} $".child"',
                'expectedIdentifierString' => '{{ {{ $".inner-parent" }} $".inner-child" }} $".child"',
            ],
            'indirectly indirect descendant' => [
                'string' => '{{ {{ {{ $".inner-inner-parent" }} $".inner-inner-child" }} $".inner-child" }} $".child"',
                'expectedIdentifierString' =>
                    '{{ {{ {{ $".inner-inner-parent" }} $".inner-inner-child" }} $".inner-child" }} $".child"',
            ],
        ];

        foreach ($dataSets as $name => $data) {
            $additionalDataName = $name . ' with additional non-relevant data';
            $data['string'] .= ' additional non-relevant data';

            $dataSets[$additionalDataName] = $data;
        }

        return $dataSets;
    }
}
