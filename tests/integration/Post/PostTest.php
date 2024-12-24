<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Unit\Post;

use Brain\Monkey\Functions;
use Widoz\Wp\Konomi\Post;
use Widoz\Wp\Konomi\User;

describe('Post', function () {
    it('count likes for posts', function () {
        $this->postMetaStorage = includeValidPostUserLikes();
        [, $getter] = setupPostMetaStorage($this->postMetaStorage);

        Functions\when('get_post_meta')->alias($getter);

        $repository = Post\Repository::new(
            '_konomi_likes',
            Post\Storage::new(),
            Post\StoredDataValidator::new(),
            User\ItemFactory::new()
        );
        $post = \Widoz\Wp\Konomi\Post\Post::new($repository);

        expect($post->countForPost(1))->toEqual(0);
        expect($post->countForPost(10))->toEqual(10);
    });
});
