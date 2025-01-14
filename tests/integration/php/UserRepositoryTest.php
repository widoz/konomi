<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Integration;

use Brain\Monkey\Actions;
use Brain\Monkey\Functions;
use Widoz\Wp\Konomi\User;

beforeAll(function (): void {
    $wpUser = \Mockery::mock(\WP_User::class);
    $wpUser->ID = 1;
    Functions\when('wp_get_current_user')->justReturn($wpUser);
});

beforeEach(function (): void {
    $this->userMetaStorage = includeValidUsersLikes();
    [$stubsCounter, $getter, $setter] = setupUserMetaStorage($this->userMetaStorage);
    $this->stubsCounter = $stubsCounter;

    Functions\when('get_user_meta')->alias($getter);
    Functions\when('update_user_meta')->alias($setter);

    $this->repository = \Widoz\Wp\Konomi\User\Repository::new(
        '_likes',
        User\Storage::new(),
        User\ItemFactory::new(),
        User\ItemCache::new(),
        User\RawDataAssert::new()
    );
});

describe('User Repository', function (): void {
    it('find an item for the user', function (): void {
        Actions\expectDone('konomi.user.repository.find')->once();
        $user = User\CurrentUser::new($this->repository);
        $item = $this->repository->find($user, 2);
        expect($item->id())->toBe(2);
        expect($item->type())->toBe('page');
    });

    it('do not load items twice from the persistance layer', function (): void {
        $user = User\CurrentUser::new($this->repository);
        $this->repository->find($user, 2);
        $this->repository->find($user, 2);
        expect(($this->stubsCounter)()['get_user_meta'])->toBe(1);
    });

    it('skip invalid stored items when loading', function (): void {
        $this->userMetaStorage[1]['_likes'] = [
            1 => [1, 'product'],
            2 => 'invalid',
            3 => [3, 'page', 'extra'],
            4 => ['not_int', 'post'],
            5 => [5, 123], // type must be string
        ];

        $user = User\CurrentUser::new($this->repository);
        $items = $this->repository->find($user, 1);

        // Only the first valid item should be loaded
        expect($items->id())->toBe(1);
        expect($items->type())->toBe('product');
    });

    it('cannot save an invalid item', function (): void {
        Actions\expectDone('konomi.user.repository.save')->never();

        $user = User\CurrentUser::new($this->repository);
        $invalidItem = User\Like::new(0, '', false);

        $result = $this->repository->save($user, $invalidItem);

        expect($result)->toBeFalse();
        expect(($this->stubsCounter)()['update_user_meta'])->toBe(0);
    });

    it('save a valid item', function (): void {
        Actions\expectDone('konomi.user.repository.save')->once();

        $user = User\CurrentUser::new($this->repository);
        $item = User\Like::new(1, 'product', true);

        $result = $this->repository->save($user, $item);

        expect($result)->toBeTrue();
        expect(($this->stubsCounter)()['update_user_meta'])->toBe(1);
        expect($this->userMetaStorage[1]['_likes'][1])->toBe([1, 'product']);
    });

    it('do not save inactive items', function (): void {
        $user = User\CurrentUser::new($this->repository);
        $inactiveItem = User\Like::new(1, 'product', false);

        expect($this->userMetaStorage[1]['_likes'])->toEqual([
            1 => [1, 'product'],
            2 => [2, 'page'],
        ]);

        $this->repository->save($user, $inactiveItem);

        expect($this->userMetaStorage[1]['_likes'])->toEqual([
            2 => [2, 'page'],
        ]);
    });
});
