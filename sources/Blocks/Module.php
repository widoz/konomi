<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks;

use Psr\Container\ContainerInterface;
use Inpsyde\Modularity\{
    Module\ExecutableModule,
    Module\ModuleClassNameIdTrait,
    Module\ServiceModule,
    Properties\Properties
};
use Widoz\Wp\Konomi\Blocks\{
    Rest\AddControllerFactory,
    Rest\AddSchemaFactory,
    Rest\AddResponse
};
use Widoz\Wp\Konomi\Rest;
use Widoz\Wp\Konomi\User;
use Widoz\Wp\Konomi\Post;

class Module implements ServiceModule, ExecutableModule
{
    use ModuleClassNameIdTrait;

    public static function new(Properties $appProperties): self
    {
        return new self($appProperties);
    }

    final private function __construct(readonly private Properties $appProperties)
    {
    }

    public function services(): array
    {
        $basePath = untrailingslashit($this->appProperties->basePath());
        $isDebug = $this->appProperties->isDebug();

        return [
            TemplateRender::class => static fn () => TemplateRender::new(
                "{$basePath}/sources/Blocks/",
                $isDebug
            ),
            BlockRegistrar::class => static fn () => BlockRegistrar::new(
                "{$basePath}/sources/Blocks",
                "{$basePath}/sources/Blocks/blocks-manifest.php"
            ),
            InstanceId::class => static fn () => InstanceId::new(),

            /*
             * Rest
             */
            AddSchemaFactory::class => static fn () => AddSchemaFactory::new(),
            AddControllerFactory::class => static fn (
                ContainerInterface $container
            ) => AddControllerFactory::new(
                $container->get(User\UserFactory::class),
                $container->get(User\ItemFactory::class),
            ),

            /*
             * Konomi
             */
            Konomi\Context::class => static fn (ContainerInterface $container) => Konomi\Context::new(
                $container->get(User\UserFactory::class),
                $container->get(InstanceId::class)
            ),

            /*
             * Like
             */
            Like\Context::class => static fn (
                ContainerInterface $container
            ) => Like\Context::new(
                $container->get(User\UserFactory::class),
                $container->get(Post\Post::class),
                $container->get(InstanceId::class)
            ),

            /*
             * Bookmark
             */
            Bookmark\Context::class => static fn (
                ContainerInterface $container
            ) => Bookmark\Context::new(
                $container->get(User\UserFactory::class),
                $container->get(InstanceId::class)
            ),
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        add_action(
            'rest_api_init',
            static function () use ($container) {
                Rest\Route::post(
                    'konomi/v1',
                    '/user-reaction',
                    $container->get(AddSchemaFactory::class)->create('React Rest Api Schema'),
                    $container->get(AddControllerFactory::class)->create(
                        User\ItemGroup::REACTION,
                        AddResponse::new(
                            User\ItemGroup::REACTION,
                            'Like saved',
                            'Invalid Like data, please contact the support or try again later.',
                            'Failed to save like'
                        )
                    )
                )
                    ->withMiddleware($container->get(Rest\Middlewares\ErrorCatch::class))
                    ->withMiddleware($container->get(Rest\Middlewares\Authentication::class))
                    ->register();
            }
        );

        add_action(
            'rest_api_init',
            static function () use ($container) {
                Rest\Route::post(
                    'konomi/v1',
                    '/user-bookmark',
                    $container->get(AddSchemaFactory::class)->create('React Rest Api Schema'),
                    $container->get(AddControllerFactory::class)->create(
                        User\ItemGroup::BOOKMARK,
                        AddResponse::new(
                            User\ItemGroup::BOOKMARK,
                            'Bookmark saved',
                            'Invalid Bookmark data, please contact the support or try again later.',
                            'Failed to save bookmark'
                        )
                    )
                )
                    ->withMiddleware($container->get(Rest\Middlewares\ErrorCatch::class))
                    ->withMiddleware($container->get(Rest\Middlewares\Authentication::class))
                    ->register();
            }
        );

        /** @var BlockRegistrar $blocksRegistrar */
        $blocksRegistrar = $container->get(BlockRegistrar::class);
        add_action('init', [$blocksRegistrar, 'registerBlockTypes']);

        return true;
    }
}
