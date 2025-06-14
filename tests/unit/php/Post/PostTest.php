<?php

declare(strict_types=1);

namespace SpaghettiDojo\Konomi\Tests\Unit\Post;

use SpaghettiDojo\Konomi\Post\{
    Post,
    Repository
};
use SpaghettiDojo\Konomi\User\{Item, ItemGroup};
use Mockery;

beforeEach(function (): void {
    /** @var \Mockery\MockInterface&Repository */
    $this->repository = Mockery::mock(Repository::class);
    $this->post = Post::new($this->repository);
    $this->item = Mockery::mock(Item::class);
});

describe('countForPost', function (): void {
    it('should count the amount of items found in the repository', function (): void {
        $this->repository->shouldReceive('find')->with(1, ItemGroup::REACTION)->andReturn([
            100 => $this->item,
            200 => $this->item,
            21 => $this->item,
        ]);
        expect($this->post->countForPost(1, ItemGroup::REACTION))->toBe(3);
    });
});
