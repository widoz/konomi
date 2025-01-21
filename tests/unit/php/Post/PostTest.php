<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Unit\Post;

use Widoz\Wp\Konomi\Post\{
    Post,
    Repository
};
use Widoz\Wp\Konomi\User\{
    Item
};
use Mockery;

beforeEach(function (): void {
    /** @var \Mockery\MockInterface&Repository */
    $this->repository = Mockery::mock(Repository::class);
    $this->post = Post::new($this->repository);
    $this->item = Mockery::mock(Item::class);
});

describe('Post', function (): void {
    describe('countForPost', function(): void {
        it('should count the amount of items found in the repository', function (): void {
            $this->repository->shouldReceive('find')->with(1)->andReturn([
                100 => $this->item,
                200 => $this->item,
                21 => $this->item,
            ]);
            expect($this->post->countForPost(1))->toBe(3);
        });
    });
});
