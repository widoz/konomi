<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Unit\Post;

use Widoz\Wp\Konomi\Post\{
    Repository,
    Storage,
    RawDataAssert
};
use Widoz\Wp\Konomi\User\{
    ItemFactory,
    Like
};
use Mockery;

beforeEach(function (): void {
    $this->key = 'key';
    $this->storage = Mockery::mock(Storage::class);
    $this->rawDataAsserter = Mockery::mock(RawDataAssert::class);
    $this->itemFactory = Mockery::mock(ItemFactory::class);
    $this->repository = Repository::new(
        $this->key,
        $this->storage,
        $this->rawDataAsserter,
        $this->itemFactory
    );
});

describe('Repository', function (): void {
    describe('find', function (): void {
        it('returns an empty array when no post is found', function (): void {
            $postId = 1;
            $this->storage->expects('read')->with($postId, $this->key)->andReturn([]);
            $this->rawDataAsserter->expects('ensureDataStructure')->with([])->andReturn((fn () => yield from [])());
            expect($this->repository->find($postId))->toBe([]);
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
            $this->itemFactory->shouldReceive('create')->andReturnUsing(fn (int $id, string $type) => Like::new($id, $type, true));
            $result = $this->repository->find($postId);

            expect($result[100]->id())->toBe(10);
            expect($result[100]->type())->toBe('post');
            expect($result[21]->id())->toBe(10);
            expect($result[21]->type())->toBe('product');
        });
    });
});
