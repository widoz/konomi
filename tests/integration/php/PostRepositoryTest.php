<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Integration\Post;

use Brain\Monkey\Functions;
use Widoz\Wp\Konomi\Post;
use Widoz\Wp\Konomi\User;

beforeEach(function (): void {
    $this->wpUser = \Mockery::mock('\WP_User');
    $this->wpUser->ID = 34;

    $this->postMetaStorage = includeValidPostUserLikes();
    [, $getter, $setter] = setupPostMetaStorage($this->postMetaStorage);

    Functions\when('get_post_meta')->alias($getter);
    Functions\when('update_post_meta')->alias($setter);
    Functions\when('wp_get_current_user')->justReturn($this->wpUser);

    $this->currentUser = User\CurrentUser::new(
        User\Repository::new(
            '_likes',
            User\Storage::new(),
            User\LikeFactory::new(),
            User\ItemRegistry::new(),
            User\RawDataAssert::new()
        )
    );
    $this->repository = Post\Repository::new(
        '_konomi_likes',
        Post\Storage::new(),
        Post\RawDataAssert::new(),
        User\LikeFactory::new()
    );
});

describe('Post Repository', function (): void {
    it('find items from post repository', function (): void {
        $items = $this->repository->find(10);

        expect($items)->toBeArray();
        expect(count($items))->toBe(10);

        foreach ($items as $userId => $userItems) {
            expect($userId)->toBeInt();
            foreach ($userItems as $item) {
                expect($item instanceof User\Item)->toBe(true);
            }
        }
    });

    it('return empty collection if nothing found', function (): void {
        $items = $this->repository->find(1);
        expect($items)->toBeArray();
        expect(count($items))->toBe(0);
    });

    it('return empty collection if the entity Id is zero', function (): void {
        $items = $this->repository->find(0);
        expect($items)->toBeArray();
        expect(count($items))->toBe(0);
    });

    it('return empty collection if the entity Id is less than 0', function (): void {
        $items = $this->repository->find(rand(-100, -1));
        expect($items)->toBeArray();
        expect(count($items))->toBe(0);
    });

    it('save items to post repository', function (): void {
        $itemToStore = User\Like::new(1, 'type', true);
        $result = $this->repository->save($itemToStore, $this->currentUser);
        $storedItem = User\Like::new(
            $this->postMetaStorage[1]['_konomi_likes'][$this->wpUser->ID][0][0],
            $this->postMetaStorage[1]['_konomi_likes'][$this->wpUser->ID][0][1],
            $itemToStore->isActive()
        );

        expect($result)->toBeTrue();

        expect($storedItem->id())->toEqual($itemToStore->id());
        expect($storedItem->type())->toEqual($itemToStore->type());
        expect($storedItem->isActive())->toEqual($itemToStore->isActive());
        expect($storedItem->isValid())->toBeTrue();
    });

    it('override existing item in post repository', function (): void {
        $itemToStore = User\Like::new(1, 'type', true);
        $this->repository->save($itemToStore, $this->currentUser);

        $itemToStore = User\Like::new(1, 'type', false);
        $result = $this->repository->save($itemToStore, $this->currentUser);

        expect($result)->toBeTrue();
        expect($this->postMetaStorage[1]['_konomi_likes'][$this->wpUser->ID])->toBeEmpty();
    });

    it('do not store invalid items', function (): void {
        $itemToStore = User\Like::new(-1, '', true);
        $this->repository->save($itemToStore, $this->currentUser);
        $result = $this->repository->save($itemToStore, $this->currentUser);

        expect($result)->toBeFalse();
        expect($this->postMetaStorage[-1]['_konomi_likes'][$this->wpUser->ID])->toBeEmpty();
    });
});
