<?php

declare(strict_types=1);

beforeAll(function () {
    setUpHooks();
});

describe('Conditional Removable Import Map Aware Hook', function (): void {
    it(
        'execute the callback when attribute type importmap is present and given condition match',
        function (): void {
            $expected = false;

            \Widoz\Wp\Konomi\Utils\ConditionalRemovableImportMapAwareHook::do(
                function (array $attributes, string $data): bool {
                    return str_contains($data, 'something-in-data');
                },
                function () use (&$expected): void {
                    $expected = true;
                }
            );

            $attributes = apply_filters(
                'wp_inline_script_attributes',
                ['type' => 'importmap'],
                'Lorem ipsum something-in-data dolor sit amet.'
            );
            expect($expected)->toEqual(true);
            expect($attributes)->toEqual(['type' => 'importmap']);
        }
    );

    it(
        'do not execute the callback when attribute type importmap is not present',
        function (): void {
            $expected = false;

            \Widoz\Wp\Konomi\Utils\ConditionalRemovableImportMapAwareHook::do(
                function (array $attributes, string $data): bool {
                    return str_contains($data, 'something-in-data');
                },
                function () use (&$expected): void {
                    $expected = true;
                }
            );

            apply_filters(
                'wp_inline_script_attributes',
                ['type' => 'script'],
                'Lorem ipsum something-in-data dolor sit amet.'
            );
            expect($expected)->toEqual(false);
        }
    );
});
