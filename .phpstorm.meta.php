<?php

namespace PHPSTORM_META {
    override(
        \Psr\Container\ContainerInterface::get(0),
        map(
            [
                '' => '@',
                'konomi.blocks.registrar' => \Widoz\Wp\Konomi\Blocks\BlockRegistrar::class,
                'konomi.blocks.like-context' => \Widoz\Wp\Konomi\Blocks\LikeContext::class,

                'konomi.configuration' => \Widoz\Wp\Konomi\Configuration\Configuration::class,

                'konomi.icons.render' => \Widoz\Wp\Konomi\Icons\Render::class,

                'konomi.likes.factory' => \Widoz\Wp\Konomi\User\Likes\LikeFactory::class,
                'konomi.likes.collection' => \Widoz\Wp\Konomi\User\Collection::class,

                'konomi.user' => \Widoz\Wp\Konomi\User\User::class,
                'konomi.user.meta.read' => \Widoz\Wp\Konomi\User\Meta\Read::class,
            ]
        )
    );
}
