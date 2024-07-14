<?php

// TODO Revert the services to use class names as keys.
namespace PHPSTORM_META {
    override(
        \Psr\Container\ContainerInterface::get(0),
        map(
            [
                '' => '@',
                'konomi.api-fetch.script-enqueue-filter' => \Widoz\Wp\Konomi\ApiFetch\ScriptEnqueueFilter::class,

                'konomi.blocks.registrar' => \Widoz\Wp\Konomi\Blocks\BlockRegistrar::class,
                'konomi.blocks.like-context' => \Widoz\Wp\Konomi\Blocks\LikeContext::class,

                'konomi.configuration' => \Widoz\Wp\Konomi\Configuration\Configuration::class,
                'konomi.configuration.init-script-render' => \Widoz\Wp\Konomi\Configuration\ConfigurationInitScriptFilter::class,

                'konomi.icons.render' => \Widoz\Wp\Konomi\Icons\Render::class,

                'konomi.user' => \Widoz\Wp\Konomi\User\User::class,
                'konomi.user.like.factory' => \Widoz\Wp\Konomi\User\Like\Factory::class,
                'konomi.user.collection' => \Widoz\Wp\Konomi\User\Collection::class,
                'konomi.user.meta' => \Widoz\Wp\Konomi\User\Meta::class,

                'konomi.rest.controller.add-like' => \Widoz\Wp\Konomi\Rest\Controller::class,
                'konomi.rest.middleware.authentication' => \Widoz\Wp\Konomi\Rest\Middleware::class,
            ]
        )
    );
}
