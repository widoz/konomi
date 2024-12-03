<?php

declare(strict_types=1);

use Brain\Monkey\Actions;
use Brain\Monkey\Functions;
use Widoz\Wp\Konomi\User;

beforeAll(function () {
    $wpUser = \Mockery::mock(\WP_User::class);
    $wpUser->ID = 1;
    Functions\when('wp_get_current_user')->justReturn($wpUser);
});

beforeEach(function() {
    $this->updateStubCallCount = function(string $functionName) {
        $this->stubsCounter[$functionName] = ++$this->stubsCounter[$functionName];
    };

    $this->stubsCounter = [
        'get_user_meta' => 0,
        'update_user_meta' => 0
    ];

    $this->userMetaStorage = [
        1 => [
            '_key' => [
                1 => [1, 'product'],
                2 => [2, 'page'],
            ],
        ],
        2 => [
            '_key' => [
                100 => [100, 'product'],
                20 => [20, 'page'],
            ],
        ],
        3 => [
            '_key' => [
                11 => [11, 'page'],
                2 => [2, 'post'],
            ],
        ],
    ];

    Functions\when('get_user_meta')->alias(
        function(int $userId, string $key) {
            ($this->updateStubCallCount)('get_user_meta');
            return $this->userMetaStorage[$userId][$key] ?? [];
        }
    );

    Functions\when('update_user_meta')->alias(
        function(int $userId, string $key, array $data) {
            ($this->updateStubCallCount)('update_user_meta');
            $this->userMetaStorage[$userId][$key] = $data;
            return true;
        }
    );

    $this->repository = \Widoz\Wp\Konomi\User\Repository::new(
        '_key',
        \Widoz\Wp\Konomi\User\Storage::new(),
        \Widoz\Wp\Konomi\User\ItemFactory::new(),
        \Widoz\Wp\Konomi\User\ItemCache::new()
    );
});

describe('Repository', function() {
    it('find an item for the user', function() {
        Actions\expectDone('konomi.user.repository.find')->once();
        $user = User\CurrentUser::new($this->repository);
        $item = $this->repository->find($user, 2);
        expect($item->id())->toBe(2);
        expect($item->type())->toBe('page');
    });

    it('do not load items twice from the persistance layer', function () {
        $user = User\CurrentUser::new($this->repository);
        $this->repository->find($user, 2);
        $this->repository->find($user, 2);
        expect($this->stubsCounter['get_user_meta'])->toBe(1);
    });

    it('skip invalid stored items when loading', function() {
        $this->userMetaStorage[1]['_key'] = [
            1 => [1, 'product'],
            2 => 'invalid',
            3 => [3, 'page', 'extra'],
            4 => ['not_int', 'post'],
            5 => [5, 123] // type must be string
        ];

        $user = User\CurrentUser::new($this->repository);
        $items = $this->repository->find($user, 1);

        // Only the first valid item should be loaded
        expect($items->id())->toBe(1);
        expect($items->type())->toBe('product');
    });

    it('cannot save an invalid item', function() {
        Actions\expectDone('konomi.user.repository.save')->never();

        $user = User\CurrentUser::new($this->repository);
        $invalidItem = User\Like::new(0, '', false);

        $result = $this->repository->save($user, $invalidItem);

        expect($result)->toBeFalse();
        expect($this->stubsCounter['update_user_meta'])->toBe(0);
    });

    it('save a valid item', function() {
        Actions\expectDone('konomi.user.repository.save')->once();

        $user = User\CurrentUser::new($this->repository);
        $item = User\Like::new(1, 'product', true);

        $result = $this->repository->save($user, $item);

        expect($result)->toBeTrue();
        expect($this->stubsCounter['update_user_meta'])->toBe(1);
        expect($this->userMetaStorage[1]['_key'][1])->toBe([1, 'product']);
    });

    it('do not save inactive items', function() {
        $user = User\CurrentUser::new($this->repository);
        $inactiveItem = User\Like::new(1, 'product', false);

        expect($this->userMetaStorage[1]['_key'])->toEqual([
            1 => [1, 'product'],
            2 => [2, 'page'],
        ]);

        $this->repository->save($user, $inactiveItem);

        expect($this->userMetaStorage[1]['_key'])->toEqual([
            2 => [2, 'page'],
        ]);
    });
});
