<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Unit\Post;

use Brain\Monkey\Functions;
use Widoz\Wp\Konomi\Post\Repository;
use Widoz\Wp\Konomi\Post\Storage;
use Widoz\Wp\Konomi\User;

describe('Repository', function() {
    it('finds items for post', function() {
        $postLikes = includeValidPostUserLikes();
        Functions\expect('get_post_meta')
            ->once()
            ->with(10, '_konomi_likes', true)
            ->andReturn($postLikes);

        $repository = Repository::new(
            '_konomi_likes',
            Storage::new(),
            User\ItemFactory::new()
        );

        /** @var array<User\Item> $items */
        $items = $repository->find(10);

        expect($items)->toBeArray();
        expect(count($items))->toBe(10);

        foreach ([1, 2, 3, 4, 5, 7, 8, 9, 10] as $userId) {
            expect(count($items[$userId]))->toBe(1);
            expect($items[$userId][0]->id())->toBe($postLikes[$userId][0][0]);
            expect($items[$userId][0]->type())->toBe($postLikes[$userId][0][1]);
        }
        expect(empty($items[6][0]))->toBe(true);
    });
});
