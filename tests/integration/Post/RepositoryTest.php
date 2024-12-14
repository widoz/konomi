<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Unit\Post;

use Brain\Monkey\Functions;
use Widoz\Wp\Konomi\Post\Repository;
use Widoz\Wp\Konomi\Post\Storage;
use Widoz\Wp\Konomi\User;

describe('Repository', function() {
    it('finds items for post', function() {
        $this->postMetaStorage = includeValidPostUserLikes();
        [, $getter, $setter] = setupPostMetaStorage($this->postMetaStorage);

        Functions\when('get_post_meta')->alias($getter);
        Functions\when('update_post_meta')->alias($setter);

        $repository = Repository::new(
            '_konomi_likes',
            Storage::new(),
            User\ItemFactory::new()
        );

        $items = $repository->find(10);

        expect($items)->toBeArray();
        expect(count($items))->toBe(10);

        foreach($items as $userId => $userItems) {
            expect($userId)->toBeInt();
            foreach($userItems as $item) {
                expect($item instanceof User\Item)->toBe(true);
            }
        }
    });
});
