<?php

declare(strict_types=1);

use Inpsyde\Modularity\Properties;

function propertiesMock(): \Mockery\MockInterface&Properties\PluginProperties
{
    return \Mockery::mock(Properties\PluginProperties::class, [
        'baseUrl' => 'http://example.com',
        'basePath' => projectRootDirectory(),
        'version' => '1.0.0',
        'isDebug' => false,
    ]);
}
