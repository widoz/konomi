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
            'konomi.user' => static fn(ContainerInterface $container) => User::new(
                $container->get('konomi.likes.collection')
            ),
            'konomi.user.meta.read' => static fn() => Likes\Meta\Read::new(),

            'konomi.likes.factory' => static fn() => Likes\LikeFactory::new(),
            'konomi.likes.collection' => static fn(
                ContainerInterface $container
            ) => Collection::new(
                $container->get('konomi.user.meta.read'),
                $container->get('konomi.likes.factory')
            ),
        ];
    }

    public function run(ContainerInterface $container) : bool
    {
        return true;
    }
}
