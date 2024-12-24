<?php

// TODO Revert the services to use class names as keys.
namespace PHPSTORM_META {

    override(
        \Psr\Container\ContainerInterface::get(0),
        map(
            [
                '' => '@',

                'konomi.blocks.registrar' => \Widoz\Wp\Konomi\Blocks\BlockRegistrar::class,
                'konomi.blocks.like-context' => \Widoz\Wp\Konomi\Blocks\Like\Context::class,
                'konomi.blocks.template-render' => \Widoz\Wp\Konomi\Blocks\TemplateRender::class,

                'konomi.configuration' => \Widoz\Wp\Konomi\Configuration\Configuration::class,
                'konomi.configuration.init-script-render' => \Widoz\Wp\Konomi\Configuration\ConfigurationInitScript::class,

                'konomi.icon' => \Widoz\Wp\Konomi\Icons\Render::class,

                'konomi.user.current' => \Widoz\Wp\Konomi\User\CurrentUser::class,
                'konomi.user.item.factory' => \Widoz\Wp\Konomi\User\ItemFactory::class,
                'konomi.user.like.repository' => \Widoz\Wp\Konomi\User\Repository::class,
                'konomi.user.storage' => \Widoz\Wp\Konomi\User\Storage::class,
                'konomi.user.item.cache' => \Widoz\Wp\Konomi\User\ItemCache::class,
                'konomi.user.raw-data-assert' => \Widoz\Wp\Konomi\User\RawDataAssert::class,

                'konomi.post' => \Widoz\Wp\Konomi\Post\Post::class,
                'konomi.post.like.repository' => \Widoz\Wp\Konomi\Post\Repository::class,
                'konomi.post.raw-data-assert' => \Widoz\Wp\Konomi\Post\RawDataAssert::class,
                'konomi.post.storage' => \Widoz\Wp\Konomi\Post\Storage::class,

                'konomi.rest.like.add-schema' => \Widoz\Wp\Konomi\Rest\Like\AddSchema::class,
                'konomi.rest.like.add-controller' => \Widoz\Wp\Konomi\Rest\Controller::class,
                'konomi.rest.middleware.authentication' => \Widoz\Wp\Konomi\Rest\Middleware::class,
                'konomi.rest.middleware.error-catch' => \Widoz\Wp\Konomi\Rest\Middleware::class,
            ]
        )
    );
}
