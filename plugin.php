<?php

/**
 * Plugin Name: Konomi
 * Author: Guido Scialfa
 * Description: A WordPress plugin to save posts as favorite using the new Interactive API.
 * Author URI: https://guidoscialfa.com/
 */

declare(strict_types=1);

use Inpsyde\Modularity;
use Widoz\Wp\Konomi;

function package(): Modularity\Package
{
    static $package;

    $projectRoot = __DIR__;

    function autoload(string $projectRoot): void
    {
        $autoloadFile = "{$projectRoot}/vendor/autoload.php";
        if (!\is_readable($autoloadFile)) {
            return;
        }
        require_once $autoloadFile;
    }

    if (!$package) {
        autoload($projectRoot);
        $properties = Modularity\Properties\PluginProperties::new(__FILE__);
        $package = Modularity\Package::new($properties);
    }

    return $package;
}

\add_action(
    'plugins_loaded',
    fn() => package()->boot()
);
