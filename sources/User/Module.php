<?php
declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

use Psr\Container\ContainerInterface;
use Inpsyde\Modularity\{
    Module\ServiceModule,
    Module\ExecutableModule,
    Module\ModuleClassNameIdTrait
};

class Module implements ServiceModule, ExecutableModule
{
    use ModuleClassNameIdTrait;

    public static function new(): self
    {
        return new self();
    }

    final private function __construct()
    {
    }

    public function services(): array
    {
        // TODO Refactor all other container services names for consistency
        return [
            'konomi.user' => static fn() => User::new(),

            'konomi.meta.read' => static fn() => Likes\Meta\Read::new(),
            'konomi.likes.like-factory' => static fn() => Likes\LikeFactory::new(),

            'konomi.likes.like-collection' => static fn(
                ContainerInterface $container
            ) => Collection::new(
                $container->get('konomi.user'),
                $container->get('konomi.meta.read'),
                $container->get('konomi.likes.like-factory')
            ),
        ];
    }

    public function run(ContainerInterface $container) : bool
    {
        return true;
    }
}
