<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi;

use Inpsyde\Modularity;

function package(): Modularity\Package
{
    /** @var Modularity\Package|null $package */
    static $package = null;

    if (!$package) {
        $pluginFilePath = dirname(__DIR__, 2) . '/konomi.php';
        $properties = Modularity\Properties\PluginProperties::new($pluginFilePath);
        $package = Modularity\Package::new($properties);
    }

    return $package;
}
