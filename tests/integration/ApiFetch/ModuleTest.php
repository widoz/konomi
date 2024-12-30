<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Integration\ApiFetch;

use Widoz\Wp\Konomi\ApiFetch\Module;

beforeEach(function (): void {
    setupWpConstants();
    $this->properties = propertiesMock();
});

describe('Api Fetch', function (): void {
    it('registers the konomi/api-fetch module', function (): void {
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
        // The Data mock the json file content
        apply_filters('wp_inline_script_attributes', ['type' => 'importmap'], '"@konomi\/api-fetch"');

        expect(wp_module_script_is('@konomi/api-fetch', 'registered'))->toBe(true);
        expect(asset('@konomi/api-fetch', 'modules', 'registered')['dependencies'])->toContain('@konomi/configuration');
        expect(wp_script_is('wp-api-fetch', 'enqueued'))->toBe(true);
    });
});
