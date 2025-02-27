<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Unit\Blocks;

use Brain\Monkey\Functions;
use Widoz\Wp\Konomi\Post;
use Widoz\Wp\Konomi\User;
use Widoz\Wp\Konomi\Blocks\Like\Context;

describe('Like Context', function (): void {
    describe('toArray', function (): void {
        it('ensure valid serialization', function (): void {
            $user = \Mockery::mock(User\User::class, [
                'isLoggedIn' => true,
                'findLike' => \Mockery::mock(User\Item::class, ['isActive' => true]),
            ]);
            $post = \Mockery::mock(Post\Post::class, [
                'countForPost' => 1,
            ]);

            Functions\expect('get_the_ID')->andReturn(10);
            Functions\expect('get_post_type')->andReturn('post-type');

            $likeContext = Context::new($user, $post);
            $likeContextAsArray = $likeContext->toArray();

            expect($likeContext->instanceId())->toEqual(1)
                ->and($likeContextAsArray['id'])->toEqual(10)
                ->and($likeContextAsArray['type'])->toEqual('post-type')
                ->and($likeContextAsArray['count'])->toEqual(1)
                ->and($likeContextAsArray['isUserLoggedIn'])->toEqual(true)
                ->and($likeContextAsArray['isActive'])->toEqual(true);
        });
    });
});
