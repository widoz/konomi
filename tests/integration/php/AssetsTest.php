<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Integration\ApiFetch;

use Widoz\Wp\Konomi\ApiFetch;
use Widoz\Wp\Konomi\Icons;

beforeEach(function (): void {
    setupWpConstants();
    $this->properties = propertiesMock();
    $this->container = new class implements \Psr\Container\ContainerInterface {
        public function get(string $id)
        {
            return null;
        }

        public function has(string $id): bool
        {
            return false;
        }
    };

    $this->modules = [
        ApiFetch\Module::new($this->properties) => function(): void {
            expect(wp_script_is('konomi-icons', 'registered'))->toBe(true);
        },
        Icons\Module::new($this->properties) => function () : void {
            expect(wp_module_script_is('@konomi/api-fetch', 'registered'))->toBe(true);
            expect(asset('@konomi/api-fetch', 'modules', 'registered')['dependencies'])->toContain('@konomi/configuration');
            expect(wp_script_is('wp-api-fetch', 'enqueued'))->toBe(true);
        },
    ];
});

describe('Assets', function(): void {
    it(
        'Ensure the assets and their dependencies are correctly registered and enqueued',
        function(): void {
            $callbacks = [];
            foreach ($this->modules as $module => $callback) {
                $module->run($this->container);
                $callbacks[] = $callback;
            }

            do_action('wp_enqueue_scripts');
            // The Data mock the json file content
            apply_filters('wp_inline_script_attributes', ['type' => 'importmap'], '"@konomi\/api-fetch"');

            foreach ($callbacks as $callback) {
                $callback();
            }
        }
    );
});
