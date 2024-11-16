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
                'konomi.blocks.like-context' => \Widoz\Wp\Konomi\Blocks\Like\Context::class,

                'konomi.configuration' => \Widoz\Wp\Konomi\Configuration\Configuration::class,
                'konomi.configuration.init-script-render' => \Widoz\Wp\Konomi\Configuration\ConfigurationInitScript::class,

                'konomi.icons.render' => \Widoz\Wp\Konomi\Icons\Render::class,

                'konomi.user.current' => \Widoz\Wp\Konomi\User\CurrentUser::class,
                'konomi.user.like.factory' => \Widoz\Wp\Konomi\User\Like\Factory::class,
                'konomi.user.collection' => \Widoz\Wp\Konomi\User\Collection::class,
                'konomi.user.storage' => \Widoz\Wp\Konomi\User\Storage::class,

                'konomi.post' => \Widoz\Wp\Konomi\Post\Post::class,
                'konomi.post.collection' => \Widoz\Wp\Konomi\Post\Collection::class,
                'konomi.post.storage' => \Widoz\Wp\Konomi\Post\Storage::class,

                'konomi.rest.controller.add-like' => \Widoz\Wp\Konomi\Rest\Controller::class,
                'konomi.rest.middleware.authentication' => \Widoz\Wp\Konomi\Rest\Middleware::class,
            ]
        )
    );
}
