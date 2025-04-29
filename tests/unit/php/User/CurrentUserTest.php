<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Unit\User;

use Mockery;
use Brain\Monkey\Functions;
use Widoz\Wp\Konomi\User;

beforeEach(function (): void {
    $this->wpUser = Mockery::mock('\WP_User');
    $this->wpUser->ID = 42;

    $this->repository = Mockery::mock(User\Repository::class);
    $this->mockItem = Mockery::mock(User\Item::class);
});

describe('CurrentUser', function (): void {
    it('returns true when user is logged in', function (): void {
        Functions\expect('wp_get_current_user')->once()->andReturn($this->wpUser);
        Functions\expect('is_user_logged_in')->once()->andReturn(true);
        $currentUser = User\CurrentUser::new($this->repository);
        expect($currentUser->isLoggedIn())->toBeTrue();
    });

    it('returns false when user is not logged in', function (): void {
        Functions\expect('wp_get_current_user')->once()->andReturn($this->wpUser);
        Functions\expect('is_user_logged_in')->once()->andReturn(false);
        $currentUser = User\CurrentUser::new($this->repository);
        expect($currentUser->isLoggedIn())->toBeFalse();
    });

    it('returns user ID when user exists', function (): void {
        Functions\expect('wp_get_current_user')->once()->andReturn($this->wpUser);
        $currentUser = User\CurrentUser::new($this->repository);
        expect($currentUser->id())->toBe($this->wpUser->ID);
    });

    it('returns 0 when user does not exist', function (): void {
        Functions\expect('wp_get_current_user')->once()->andReturn(null);
        $currentUser = User\CurrentUser::new($this->repository);
        expect($currentUser->id())->toBe(0);
    });

    it('find Item', function (): void {
        $itemId = 123;
        Functions\expect('wp_get_current_user')->once()->andReturn($this->wpUser);
        $currentUser = User\CurrentUser::new($this->repository);
        $this->repository->shouldReceive('find')->once()->with($currentUser, $itemId)->andReturn($this->mockItem);
        $result = $currentUser->findLike($itemId);
        expect($result)->toBe($this->mockItem);
    });

    it('save Item', function (): void {
        Functions\expect('wp_get_current_user')->once()->andReturn($this->wpUser);
        $currentUser = User\CurrentUser::new($this->repository);
        $item = User\Like::new(123, 'post', true);
        $this->repository->shouldReceive('save')->once()->with($currentUser, $item);
        $currentUser->saveLike($item);
    });
});
