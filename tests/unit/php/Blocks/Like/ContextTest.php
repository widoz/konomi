<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Unit\Blocks;

use Brain\Monkey\Functions;
use Widoz\Wp\Konomi\Blocks;
use Widoz\Wp\Konomi\Post;
use Widoz\Wp\Konomi\User;
use Widoz\Wp\Konomi\Blocks\Like\Context;

describe('Like Context', function (): void {
    describe('toArray', function (): void {
        it('ensure valid serialization', function (): void {
            $user = \Mockery::mock(User\User::class, [
                'isLoggedIn' => true,
                'findItem' => \Mockery::mock(User\Item::class, ['isActive' => true]),
            ]);
            $userFactory = \Mockery::mock(User\UserFactory::class, [
                'create' => $user,
            ]);
            $post = \Mockery::mock(Post\Post::class, [
                'countForPost' => 1,
            ]);
            $instanceId = \Mockery::mock(Blocks\InstanceId::class);

            Functions\expect('get_the_ID')->andReturn(10);
            Functions\expect('get_post_type')->andReturn('post-type');

            $likeContext = Context::new($userFactory, $post, $instanceId);
            $likeContextAsArray = $likeContext->toArray();

            expect($likeContext->instanceId())->toEqual($instanceId)
                ->and($likeContextAsArray['count'])->toEqual(1)
                ->and($likeContextAsArray['isActive'])->toEqual(true);
        });
    });
});
