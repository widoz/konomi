<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User;

class Collection
{
    public static function new(User $user, Meta\Read $read, ItemFactory $itemFactory): Collection
    {
        return new self($user, $read, $itemFactory);
    }

    final private function __construct(
        readonly private User $user,
        readonly private Meta\Read $read,
        readonly private ItemFactory $itemFactory
    ) {}

    public function items(): \Generator
    {
        if (!$this->user->isLoggedIn()) {
            return;
        }

        foreach ($this->collectRawItems() as [$id, $type]) {
            yield $this->itemFactory->create($id, $type);
        }
    }

    private function collectRawItems(): array
    {
        return $this->read->read();
    }
}
