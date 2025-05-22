<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Unit\Post;

use Widoz\Wp\Konomi\Post\{
    Repository,
    Storage,
    StorageKey,
    RawDataAssert
};
use Widoz\Wp\Konomi\User\{Item, ItemFactory, ItemGroup, User};
use Mockery;
use Brain\Monkey\Actions;

beforeEach(function (): void {
    $this->key = 'key';
    $this->storage = Mockery::mock(Storage::class);
    $this->rawDataAsserter = Mockery::mock(RawDataAssert::class);
    $this->itemFactory = Mockery::mock(ItemFactory::class);
    $this->storageKey = Mockery::mock(StorageKey::class, ['for' => $this->key]);
    $this->repository = Repository::new(
        $this->storageKey,
        $this->storage,
        $this->rawDataAsserter,
        $this->itemFactory
    );
});

describe('find', function (): void {
    it('returns an empty array when no post is found', function (): void {
        $postId = 1;
        $this->storage->expects('read')->with($postId, $this->key)->andReturn([]);
        $this->rawDataAsserter->expects('ensureDataStructure')->with([])->andReturn((fn () => yield from [])());
        expect($this->repository->find($postId, ItemGroup::REACTION))->toBe([]);
    });

    it('returns post data when post exists', function (): void {
        $postId = 10;
        $rawData = [
            100 => [
                [10, 'post'],
            ],
            21 => [
                [10, 'product'],
            ],
        ];

        $this->storage->expects('read')->with($postId, $this->key)->andReturn($rawData);
        $this->rawDataAsserter->expects('ensureDataStructure')->with($rawData)->andReturn((fn () => yield from $rawData)());
        $this->itemFactory->shouldReceive('create')->andReturnUsing(fn (int $id, string $type, bool $isActive, $group) => Item::new($id, $type, $isActive, $group));
        $result = $this->repository->find($postId, ItemGroup::REACTION);

        expect($result[100]->id())->toBe(10);
        expect($result[100]->type())->toBe('post');
        expect($result[21]->id())->toBe(10);
        expect($result[21]->type())->toBe('product');
    });
});

describe('save', function (): void {
    it('returns false when item is invalid', function (): void {
        $invalidItem = Mockery::mock(Item::class, ['isValid' => false]);
        $user = Mockery::mock(User::class);
        expect($this->repository->save($invalidItem, $user))->toBeFalse();
        Actions\expectAdded('konomi.post.collection.save')->never();
    });

    it('adds new item when it does not exist', function (): void {
        $postId = 10;
        $userId = 1;
        $item = Item::new($postId, 'post', true);
        /** @var User&\Mockery\MockInterface $user */
        $user = Mockery::mock(User::class);

        $user->shouldReceive('id')->andReturn($userId);
        $this->storage->expects('read')->with($postId, $this->key)->andReturn([]);
        $this->rawDataAsserter->expects('ensureDataStructure')->with([])->andReturn((fn () => yield from [])());
        $this->storage->expects('write')->with($postId, $this->key, [$userId => [[$postId, 'post']]])->andReturn(true);

        expect($this->repository->save($item, $user))->toBeTrue();
    });

    it('removes item when it exists', function (): void {
        $postId = 10;
        $userId = 1;
        $existingData = [$userId => [[$postId, 'post']]];
        $item = Item::new($postId, 'post', true);
        /** @var User&\Mockery\MockInterface $user */
        $user = Mockery::mock(User::class);

        $user->shouldReceive('id')->andReturn($userId);
        $this->storage->expects('read')->with($postId, $this->key)->andReturn($existingData);
        $this->rawDataAsserter->expects('ensureDataStructure')->with($existingData)->andReturn((fn () => yield from $existingData)());
        $this->storage->expects('write')->with($postId, $this->key, [$userId => []])->andReturn(true);

        expect($this->repository->save($item, $user))->toBeTrue();
    });
});
