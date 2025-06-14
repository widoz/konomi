<?php

declare(strict_types=1);

namespace SpaghettiDojo\Konomi\Post;

use Psr\Container\ContainerInterface;
use Inpsyde\Modularity\{
    Module\ServiceModule,
    Module\ExecutableModule,
    Module\ModuleClassNameIdTrait
};
use SpaghettiDojo\Konomi\User;

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
            Post::class => static fn (ContainerInterface $container) => Post::new(
                $container->get(Repository::class)
            ),
            Storage::class => static fn () => Storage::new(),
            RawDataAssert::class => static fn () => RawDataAssert::new(),
            StorageKey::class => static fn () => StorageKey::new('_konomi_items'),
            Repository::class => static fn (
                ContainerInterface $container
            ) => Repository::new(
                $container->get(StorageKey::class),
                $container->get(Storage::class),
                $container->get(RawDataAssert::class),
                $container->get(User\ItemFactory::class)
            ),
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        add_action(
            'konomi.user.repository.save',
            static fn (User\Item $item, User\User $user) => $container
                ->get(Repository::class)
                ->save($item, $user),
            10,
            2
        );

        return true;
    }
}
