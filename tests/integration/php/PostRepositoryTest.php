<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Integration\Post;

use Brain\Monkey\Functions;
use Widoz\Wp\Konomi\Post;
use Widoz\Wp\Konomi\User;

beforeEach(function (): void {
    $this->postMetaStorage = includeValidPostUserLikes();
    [, $getter, $setter] = setupPostMetaStorage($this->postMetaStorage);

    Functions\when('get_post_meta')->alias($getter);
    Functions\when('update_post_meta')->alias($setter);

    $this->repository = Post\Repository::new(
        '_konomi_likes',
        Post\Storage::new(),
        Post\RawDataAssert::new(),
        User\ItemFactory::new()
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
});
