<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks;

use Inpsyde\Modularity\{
    Module\ExecutableModule,
    Module\ModuleClassNameIdTrait,
    Module\ServiceModule,
    Properties\Properties
};
use Psr\Container\ContainerInterface;
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

            /**
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
            Like\Rest\AddSchema::class => static fn () => Like\Rest\AddSchema::new(),
            Like\Rest\AddController::class => static fn (
                ContainerInterface $container
            ) => Like\Rest\AddController::new(
                $container->get(User\UserFactory::class),
                $container->get(User\ItemFactory::class)
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
            Bookmark\Rest\AddSchema::class => static fn () => Bookmark\Rest\AddSchema::new(),
            Bookmark\Rest\AddController::class => static fn (
                ContainerInterface $container
            ) => Bookmark\Rest\AddController::new(
                $container->get(User\UserFactory::class),
                $container->get(User\ItemFactory::class)
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
                    '/user-like',
                    $container->get(Like\Rest\AddSchema::class),
                    $container->get(Like\Rest\AddController::class)
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
                    $container->get(Bookmark\Rest\AddSchema::class),
                    $container->get(Bookmark\Rest\AddController::class)
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
