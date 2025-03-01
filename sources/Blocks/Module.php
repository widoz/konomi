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
use Widoz\Wp\Konomi\Blocks\Like\Context;

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
            'konomi.blocks.template-render' => static fn () => TemplateRender::new(
                "{$basePath}/sources/Blocks/",
                $isDebug
            ),
            'konomi.blocks.registrar' => static fn () => BlockRegistrar::new(
                "{$basePath}/sources/Blocks"
            ),
            'konomi.blocks.like.context' => static fn (
                ContainerInterface $container
            ) => Context::new(
                $container->get('konomi.user.current'),
                $container->get('konomi.post')
            ),

            'konomi.blocks.like.rest.add-schema' => static fn () => Like\Rest\AddSchema::new(),
            'konomi.blocks.like.rest.add-controller' => static fn (
                ContainerInterface $container
            ) => Like\Rest\AddController::new(
                $container->get('konomi.user.current'),
                $container->get('konomi.user.like-factory')
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
                    $container->get('konomi.blocks.like.rest.add-schema'),
                    $container->get('konomi.blocks.like.rest.add-controller')
                )
                    ->withMiddleware($container->get('konomi.rest.middleware.error-catch'))
                    ->withMiddleware($container->get('konomi.rest.middleware.authentication'))
                    ->register();
            }
        );

        add_action('init', [$container->get('konomi.blocks.registrar'), 'registerBlockTypes']);

        return true;
    }
}
