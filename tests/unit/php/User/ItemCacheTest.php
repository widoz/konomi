<?php

declare(strict_types=1);

use Widoz\Wp\Konomi\User\ItemCache;
use Widoz\Wp\Konomi\User\NullItem;
use Widoz\Wp\Konomi\User\User;
use Widoz\Wp\Konomi\User\Item;

beforeEach(function () {
    $this->cache = ItemCache::new();
    $this->user = Mockery::mock(User::class);
    $this->item = Mockery::mock(Item::class);
});

describe('Item Cache', function () {
    it('checks if user has items group', function () {
        expect($this->cache->hasGroup($this->user))->toBeFalse();
    });

    it('checks if user has specific item', function () {
        $this->item->shouldReceive('id')->andReturn(1);
        expect($this->cache->has($this->user, $this->item))->toBeFalse();
    });

    it('gets null item when user has no items', function () {
        expect($this->cache->get($this->user, 1))->toBeInstanceOf(NullItem::class);
    });

    it('sets and gets item for user', function () {
        $this->item->shouldReceive('id')->andReturn(1);
        $this->item->shouldReceive('isValid')->andReturn(true);

        $this->cache->set($this->user, $this->item);
        expect($this->cache->has($this->user, $this->item))->toBeTrue();
    });

    it('unsets item for user', function () {
        $this->item->shouldReceive('id')->andReturn(1);
        $this->item->shouldReceive('isValid')->andReturn(true);

        $this->cache->set($this->user, $this->item);
        $this->cache->unset($this->user, $this->item);
        expect($this->cache->has($this->user, $this->item))->toBeFalse();
    });

    it('gets all items for user', function () {
        expect($this->cache->all($this->user))->toBeArray()->toBeEmpty();

        $this->item->shouldReceive('id')->andReturn(1);
        $this->item->shouldReceive('isValid')->andReturn(true);

        $this->cache->set($this->user, $this->item);
        expect($this->cache->all($this->user))->toBeArray()->toHaveCount(1);
    });

    it('do not unset an item if not present in the cache', function () {
        $this->item->shouldReceive('id')->andReturn(1);
        $this->item->shouldReceive('isValid')->andReturn(true);

        $this->cache->unset($this->user, $this->item);
        expect($this->cache->has($this->user, $this->item))->toBeFalse();
    });

    it('return null item object if the given Item ID does not exists for the user', function () {
        $this->item->shouldReceive('id')->andReturn(1);
        $this->item->shouldReceive('isValid')->andReturn(true);

        $this->cache->set($this->user, $this->item);
        expect($this->cache->get($this->user, 2))->toBeInstanceOf(NullItem::class);
    });
});
