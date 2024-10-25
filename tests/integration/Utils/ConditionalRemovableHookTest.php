<?php

declare(strict_types=1);

use Widoz\Wp\Konomi\Utils\ConditionalRemovableHook;

beforeAll(function () {
    setUpHooks();
});

describe('Conditional Removable Hook', function (): void {
    it('Add a filter that return a changed value', function (): void {
        \Widoz\Wp\Konomi\Utils\ConditionalRemovableHook::add(
            'hook_name',
            static function (ConditionalRemovableHook $that, int $value): int {
                $that->remove();
                return $value + 1;
            }
        );

        $result = apply_filters('hook_name', 1);

        expect($result)->toEqual(2);
        expect(has_filter('hook_name'))->toBeFalse();
    });

    it('It pass all arguments given by the apply_filter function', function (): void {
        $params = ['something', 1, []];
        $expected = [];

        \Widoz\Wp\Konomi\Utils\ConditionalRemovableHook::add(
            'hook_name',
            static function (
                ConditionalRemovableHook $that,
                mixed ...$arguments
            ) use (
                &$expected
            ): string {
                $that->remove();
                $expected = $arguments;
                return $arguments[0];
            },
            10,
            3
        );

        apply_filters('hook_name', ...$params);
        expect($expected)->toEqual($params);
    });

    it('Add an action that return nothing', function (): void {
        $expected = 0;

        \Widoz\Wp\Konomi\Utils\ConditionalRemovableHook::add(
            'hook_name',
            static function (ConditionalRemovableHook $that, int $value) use (&$expected): void {
                $that->remove();
                $expected = $value;
            }
        );

        $result = do_action('hook_name', 1);

        expect($result)->toEqual(null);
        expect(has_action('hook_name'))->toBeFalse();
    });
});
