<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Post;

use Psr\Container\ContainerInterface;
use Inpsyde\Modularity\{
    Module\ServiceModule,
    Module\ExecutableModule,
    Module\ModuleClassNameIdTrait
};
use Widoz\Wp\Konomi\User;

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
        return [
            'konomi.post' => static fn (ContainerInterface $container) => Post::new(
                $container->get('konomi.post.collection')
            ),
            'konomi.post.storage' => static fn () => Storage::new(),
            'konomi.post.collection' => static fn (
                ContainerInterface $container
            ) => Collection::new(
                $container->get('konomi.post.storage'),
                $container->get('konomi.user.like.factory')
            ),
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        $collection = $container->get('konomi.post.collection');
        add_action(
            'konomi.user.collection.save',
            static function (User\Item $item, User\User $user) use ($collection): void {
                $collection->save($item, $user);
            },
            10,
            2
        );

        return true;
    }
}
