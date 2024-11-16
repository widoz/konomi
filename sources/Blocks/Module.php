<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks;

use Inpsyde\Modularity\{Module\ExecutableModule,
    Module\ModuleClassNameIdTrait,
    Module\ServiceModule,
    Properties\Properties
};
use Psr\Container\ContainerInterface;
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
            'konomi.blocks.like-context' => static fn (
                ContainerInterface $container
            ) => Context::new(
                $container->get('konomi.user.current'),
                $container->get('konomi.post')
            ),
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        add_action('init', [$container->get('konomi.blocks.registrar'), 'registerBlockTypes']);

        return true;
    }
}
