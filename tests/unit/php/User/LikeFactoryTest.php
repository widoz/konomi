<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Unit\User;

use Widoz\Wp\Konomi\User\{ItemFactory, Item, ItemGroup};

describe('ItemFactory', function (): void {
    it('should create a new Item instance', function (): void {
        $item = ItemFactory::new()->create(1, 'type', true, ItemGroup::REACTION);
        expect($item)->toBeInstanceOf(Item::class)
            ->and($item->id())->toEqual(1)
            ->and($item->type())->toEqual('type')
            ->and($item->isActive())->toBeTrue()
            ->and($item->isValid())->toBeTrue();
    });
});
