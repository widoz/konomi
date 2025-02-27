<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Integration\ApiFetch;

use Widoz\Wp\Konomi\ApiFetch;
use Widoz\Wp\Konomi\Configuration;
use Widoz\Wp\Konomi\Icons;

beforeEach(function (): void {
    setupWpConstants();
    $this->properties = propertiesMock();
    $this->container = new class implements \Psr\Container\ContainerInterface {
        /** @var array<mixed> $services */
        private array $services = [];

        public function get(string $id): mixed
        {
            if (!$this->has($id)) {
                // phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
                throw new \Error("Service {$id} not found");
            }
            return $this->services[$id]($this);
        }

        public function has(string $id): bool
        {
            return isset($this->services[$id]);
        }

        public function set(string $id, mixed $service): void
        {
            $this->services[$id] = $service;
        }
    };

    $this->modules = [
        [
            ApiFetch\Module::new($this->properties),
            function (): void {
                expect(wp_module_script_is('@konomi/api-fetch', 'registered'))->toBe(true);
                expect(asset('@konomi/api-fetch', 'modules', 'registered')['dependencies'])->toContain('@konomi/configuration');
                expect(wp_script_is('wp-api-fetch', 'enqueued'))->toBe(true);
            },
        ],
        [
            Configuration\Module::new($this->properties, ''),
            function (): void {
                // Be sure the script is registered and the configuration storage is configured.
                expect(wp_script_is('konomi-configuration', 'registered'))->toBe(true);
                expect(asset('konomi-configuration', 'scripts', 'inline')['position'])->toBe('after');
                expect(asset('konomi-configuration', 'scripts', 'inline')['data'])->toContain('window.konomiConfiguration.initConfiguration');

                // Be sure the module is registered and the configuration storage is configured.
                expect(wp_module_script_is('@konomi/configuration', 'registered'))->toBe(true);
                expect(html())->toContain("<script type=\"module\">import { initConfiguration } from '@konomi/configuration';\ninitConfiguration");
            },
        ],
        [
            Icons\Module::new($this->properties),
            function (): void {
                expect(wp_script_is('konomi-icons', 'registered'))->toBe(true);
            },
        ],
    ];

    foreach ($this->modules as [$module]) {
        foreach ($module->services() as $key => $service) {
            $this->container->set($key, $service);
        }
    }
});

describe('Assets', function (): void {
    it(
        'Ensure the assets and their dependencies are correctly registered and enqueued',
        function (): void {
            $callbacks = [];
            foreach ($this->modules as [$module, $callback]) {
                $module->run($this->container);
                $callbacks[] = $callback;
            }

            do_action('wp_enqueue_scripts');
            do_action('enqueue_block_editor_assets');
            // The Data mock the json file content
            apply_filters(
                'wp_inline_script_attributes',
                ['type' => 'importmap'],
                '"@konomi\/api-fetch", "@konomi\/configuration"'
            );
            do_action('wp_footer');

            foreach ($callbacks as $callback) {
                $callback();
            }
        }
    );
});
