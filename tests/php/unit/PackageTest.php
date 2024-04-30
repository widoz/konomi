<?php

declare(strict_types=1);

use Brain\Monkey\Functions;

it('bootstrap the package during plugins_loaded action', function () {
    $properties =  Mockery::mock('alias:Inpsyde\Modularity\Properties\PluginProperties');
    $properties->shouldReceive('new')->andReturnSelf();

    $package = Mockery::mock('alias:Inpsyde\Modularity\Package');
    $package->shouldReceive('new')->with($properties)->andReturn($package);

    $package->expects('boot');

    Functions\expect('add_action')
        ->once()
        ->andReturnUsing(static function (string $hookName, callable $callback) {
            expect($hookName)->toEqual('plugins_loaded');
            $callback();
        });

    require_once \dirname(__DIR__, 3) . '/konomi.php';
});
