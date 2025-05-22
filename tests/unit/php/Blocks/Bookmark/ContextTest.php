<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Unit\Blocks;

use Brain\Monkey\Functions;
use Widoz\Wp\Konomi\Blocks;
use Widoz\Wp\Konomi\User;
use Widoz\Wp\Konomi\Blocks\Bookmark\Context;

describe('toArray', function (): void {
    it('ensure valid serialization', function (): void {
        $user = \Mockery::mock(User\User::class, [
            'isLoggedIn' => true,
            'findItem' => \Mockery::mock(User\Item::class, ['isActive' => true]),
        ]);
        $userFactory = \Mockery::mock(User\UserFactory::class, [
            'create' => $user,
        ]);
        $instanceId = \Mockery::mock(Blocks\InstanceId::class);

        Functions\expect('get_the_ID')->andReturn(10);

        $bookmarkContext = Context::new($userFactory, $instanceId);
        $bookmarkContextAsArray = $bookmarkContext->toArray();

        expect($bookmarkContext->instanceId())->toEqual($instanceId)
            ->and($bookmarkContextAsArray['isActive'])->toEqual(true);
    });
});
