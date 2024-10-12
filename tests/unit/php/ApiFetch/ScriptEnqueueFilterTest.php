<?php

declare(strict_types=1);

use Brain\Monkey\Functions;
use Widoz\Wp\Konomi\Utils;
use Widoz\Wp\Konomi\ApiFetch;

describe('Script Enqueue Filter', function (): void {
    it(
        'enqueue the wp api fetch when konomi api-fetch module is part of the module map',
        function (): void {
            $crh = Mockery::mock('alias:' . Utils\ConditionalRemovableHook::class, [
                'remove' => function (): void {
                },
            ]);

            $crh
                ->expects('filter')
                ->andReturnUsing(
                    fn (string $name, callable $callback) => $callback(
                        $crh,
                        ['type' => 'importmap'],
                        '{"imports":{"@konomi\/api-fetch":""'
                    )
                );

            Functions\expect('wp_enqueue_script')->once()->with('wp-api-fetch');

            ApiFetch\ScriptEnqueueFilter::new()->addFilter();
        }
    );

    it(
        'does not enqueue the wp api fetch when konomi api-fetch module is not part of the module map',
        function (): void {
            $crh = Mockery::mock('alias:' . Utils\ConditionalRemovableHook::class, [
                'remove' => function (): void {
                },
            ]);

            $crh
                ->expects('filter')
                ->andReturnUsing(
                    fn (string $name, callable $callback) => $callback(
                        $crh,
                        ['type' => 'importmap'],
                        '{"imports":{"@konomi\/api-fetch2":""'
                    )
                );

            Functions\expect('wp_enqueue_script')->never();

            ApiFetch\ScriptEnqueueFilter::new()->addFilter();
        }
    );
});
