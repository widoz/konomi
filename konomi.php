<?php

/**
 * Plugin Name: Konomi
 * Author: Guido Scialfa
 * Description: A WordPress plugin to save posts as favorite using the new Interactive API.
 * Author URI: https://guidoscialfa.com/
 */

// phpcs:disable PSR1.Files.SideEffects

declare(strict_types=1);

namespace Widoz\Wp\Konomi;

add_action(
    'plugins_loaded',
    static function () {
        // phpcs:disable Squiz.PHP.InnerFunctions.NotAllowed
        function autoload(string $projectRoot): void
        {
            // phpcs:enable Squiz.PHP.InnerFunctions.NotAllowed

            $autoloadFile = "{$projectRoot}/vendor/autoload.php";
            if (!\is_readable($autoloadFile)) {
                return;
            }
            /** @psalm-suppress UnresolvableInclude */
            require_once $autoloadFile;
        }

        autoload(__DIR__);
        $package = package();
        $properties = $package->properties();

        $package
            ->addModule(Configuration\Module::new($properties))
            ->addModule(Icons\Module::new($properties))
            ->addModule(Blocks\Module::new($properties))
            ->boot();
    }
);
