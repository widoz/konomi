<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks;

use Psr\Container\ContainerInterface;
use Inpsyde\Modularity\{
    Module\ServiceModule,
    Module\ExecutableModule,
    Module\ModuleClassNameIdTrait,
    Properties\Properties
};

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
        return [
            'konomi.blocks.template-render' => fn () => TemplateRender::new(
                "{$this->appProperties->basePath()}/sources/Blocks/",
                $this->appProperties->isDebug()
            ),
            'konomi.blocks.registrar' => fn () => BlockRegistrar::new(
                "{$this->appProperties->basePath()}/sources/Blocks"
            ),
            'konomi.blocks.like-context' => static fn (
                ContainerInterface $container
            ) => LikeContext::new(
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
