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
                $container->get('konomi.post.like.repository')
            ),
            'konomi.post.storage' => static fn () => Storage::new(),
            'konomi.post.raw-data-validator' => static fn () => RawDataValidator::new(),
            'konomi.post.like.repository' => static fn (
                ContainerInterface $container
            ) => Repository::new(
                '_konomi_likes',
                $container->get('konomi.post.storage'),
                $container->get('konomi.post.raw-data-validator'),
                $container->get('konomi.user.item.factory')
            ),
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        add_action(
            'konomi.user.repository.save',
            static fn (User\Item $item, User\User $user) => $container
                ->get('konomi.post.like.repository')
                ->save($item, $user),
            10,
            2
        );

        return true;
    }
}
