<?php

declare(strict_types=1);

namespace SpaghettiDojo\Konomi\Tests\Integration\Functions;

use function SpaghettiDojo\Konomi\Functions\add_single_conditional_filter;
use function SpaghettiDojo\Konomi\Functions\add_action_on_module_import;

beforeAll(function (): void {
    setUpHooks();
});

describe('Add Single Conditional Filter', function (): void {
    it('Add script module filter', function (): void {
        $name = 'wp-hook-name';
        $expected = [];
        $condition = function (): bool {
            return true;
        };
        $callback = function (string $argument1, string $argument2) use (&$expected): string {
            $expected = func_get_args();
            return $argument1;
        };

        add_single_conditional_filter($name, $condition, $callback);

        /** @var mixed $result */
        $result = apply_filters($name, 'first-argument', 'second-argument');

        expect($expected)
            ->toEqual(['first-argument', 'second-argument'])
            ->and($result)
            ->toEqual('first-argument')
            ->and(has_filter($name))
            ->toBeFalse();
    });

    it('Do not run the callback until the condition return true', function (): void {
        $name = 'wp-hook-name';
        $expected = 0;
        $condition = fn (mixed ...$args) => $args[0] === 'first-argument-2';
        $callback = function (string $argument1) use (&$expected): string {
            $expected += 1;
            return $argument1;
        };

        add_single_conditional_filter($name, $condition, $callback);

        apply_filters($name, 'first-argument', 'second-argument');
        apply_filters($name, 'first-argument-2', 'second-argument-2');

        expect($expected)->toEqual(1);
    });

    it('Different hooks are removed separately after the callback run', function (): void {
        $name = 'wp-hook-name';
        $condition = static fn () => true;
        $counter = 0;

        add_single_conditional_filter(
            $name,
            $condition,
            function (array $collector) use (&$counter): array {
                ++$counter;
                $collector[] = 'first-hook';
                return $collector;
            }
        );

        add_single_conditional_filter(
            $name,
            static function () use (&$counter): bool {
                return $counter % 2 === 0;
            },
            function (array $collector): array {
                $collector[] = 'third-hook';
                return $collector;
            }
        );

        add_single_conditional_filter(
            $name,
            $condition,
            function (array $collector) use (&$counter): array {
                ++$counter;
                $collector[] = 'second-hook';
                return $collector;
            }
        );

        $collector = apply_filters($name, []);
        expect($collector[0])->toEqual('first-hook');
        expect($collector[1])->toEqual('second-hook');

        $collector = apply_filters($name, []);
        expect($collector[0])->toEqual('third-hook');
    });
});

describe('Do Action on Module Import', function (): void {
    it('Hook to wp_inline_script_attributes a single event callback', function (): void {
        $expected = false;
        add_action_on_module_import(
            'module-name',
            static function () use (&$expected): void {
                $expected = true;
            }
        );

        $result = apply_filters(
            'wp_inline_script_attributes',
            [
                'type' => 'importmap',
            ],
            'Lorem ipsum module-name dolor sit amet.'
        );

        expect($result)->toEqual(['type' => 'importmap']);
        expect($expected)->toBeTrue();
    });

    it('Do not run the callback if the condition return a falsy value', function (): void {
        $expected = false;
        add_action_on_module_import(
            'module-name',
            static function () use (&$expected): void {
                $expected = true;
            }
        );

        apply_filters(
            'wp_inline_script_attributes',
            [
                'type' => 'script',
            ],
            'Lorem ipsum module-name dolor sit amet.'
        );

        expect($expected)->toBeFalse();
    });
});
