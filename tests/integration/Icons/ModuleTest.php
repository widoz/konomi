<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Integration\Icons;

use Widoz\Wp\Konomi\Icons\Module;

beforeEach(function (): void {
    setupWpConstants();
    $this->properties = propertiesMock();
});

describe('Icons', function (): void {
    it('register the konomi.icon service', function (): void {
        $module = Module::new($this->properties);
        $services = $module->services();

        expect($services)->toHaveKey('konomi.icon');
    });

    it('registers the konomi-icons script', function (): void {
        $module = Module::new($this->properties);
        $module->run(new class implements \Psr\Container\ContainerInterface {
            public function get(string $id)
            {
                return null;
            }

            public function has(string $id): bool
            {
                return false;
            }
        });

        do_action('wp_enqueue_scripts');

        expect(wp_script_is('konomi-icons', 'registered'))->toBe(true);
    });
});
