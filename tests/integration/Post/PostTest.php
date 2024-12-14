<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Unit\Post;

use Brain\Monkey\Functions;
use Widoz\Wp\Konomi\Post\Repository;
use Widoz\Wp\Konomi\Post\Storage;
use Widoz\Wp\Konomi\User;

describe('Post', function() {
    it('count likes for posts', function() {
        Functions\when('get_post_meta')->alias(includeValidPostUserLikes());

        $repository = Repository::new('_konomi_likes', Storage::new(), User\ItemFactory::new());
        $post = \Widoz\Wp\Konomi\Post\Post::new($repository);

        expect($post->countForPost(1))->toEqual(0);
        expect($post->countForPost(10))->toEqual(10);
    });
});
