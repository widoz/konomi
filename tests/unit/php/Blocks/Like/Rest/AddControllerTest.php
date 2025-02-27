<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Unit\Blocks\Like\Rest;

use Mockery;
use Widoz\Wp\Konomi\Blocks;
use Widoz\Wp\Konomi\User;

beforeAll(function (): void {
    setUpWpRest();
    setUpWpError();
});

beforeEach(function (): void {
    /** @var Mockery\MockInterface&User\User $user */
    $this->user = Mockery::mock(User\User::class);
    /** @var Mockery\MockInterface&User\ItemFactory $likeFactory */
    $this->likeFactory = Mockery::mock(User\ItemFactory::class);
    /** @var Mockery\MockInterface&User\Item $like */
    $this->like = Mockery::mock(User\Item::class);
    /** @var Mockery\MockInterface&\WP_REST_Request $request */
    $this->request = Mockery::mock(\WP_REST_Request::class);
});

describe('Add Controller', function (): void {
    describe('__invoke', function (): void {
        it('Successfully save a Like', function (): void {
            $id = 1;
            $type = 'post';
            $isActive = true;
            $rawRequestData = makeRequestData($id, $type, $isActive);

            $this->request->shouldReceive('get_param')->with('meta')->andReturn($rawRequestData);
            $this->likeFactory->shouldReceive('create')->with($id, $type, $isActive)->andReturn($this->like);
            $this->like->shouldReceive('isValid')->andReturn(true);
            $this->user->shouldReceive('saveLike')->with($this->like)->andReturn(true);

            $controller = Blocks\Like\Rest\AddController::new($this->user, $this->likeFactory);
            $result = $controller($this->request);

            expect($result->get_status())->toBe(201);
            expect($result->get_data()['success'])->toBe(true);
            expect($result->get_data()['message'])->toContain('Like saved');
        });

        /**
         * In this test the request values do not matter much but the
         * `Item::isValid` is what makes the difference.
         */
        it('Fails to save the Like because of invalid data', function (): void {
            $id = 1;
            $type = 'post';
            $isActive = true;
            $rawRequestData = makeRequestData($id, $type, $isActive);

            $this->request->shouldReceive('get_param')->with('meta')->andReturn($rawRequestData);
            $this->likeFactory->shouldReceive('create')->with($id, $type, $isActive)->andReturn($this->like);
            $this->like->shouldReceive('isValid')->andReturn(false);

            $controller = Blocks\Like\Rest\AddController::new($this->user, $this->likeFactory);
            $result = $controller($this->request);

            expect($result->get_error_code())->toContain('invalid_like_data');
            expect($result->get_error_message())->toContain('Invalid Like Data');
            expect($result->get_error_data()['status'])->toBe(400);
        });

        /**
         * In this test the Request values do not matter much but the
         * `User::saveLike`.
         */
        it('Fails to save the Like', function (): void {
            $id = 1;
            $type = 'post';
            $isActive = true;
            $rawRequestData = makeRequestData($id, $type, $isActive);

            $this->request->shouldReceive('get_param')->with('meta')->andReturn($rawRequestData);
            $this->likeFactory->shouldReceive('create')->with($id, $type, $isActive)->andReturn($this->like);
            $this->like->shouldReceive('isValid')->andReturn(true);
            $this->user->shouldReceive('saveLike')->with($this->like)->andReturn(false);

            $controller = Blocks\Like\Rest\AddController::new($this->user, $this->likeFactory);
            $result = $controller($this->request);

            expect($result->get_error_code())->toContain('failed_to_save_like');
            expect($result->get_error_message())->toContain('Failed to save like');
            expect($result->get_error_data()['status'])->toBe(500);
        });
    });
});

function makeRequestData(int $id, string $type, bool $isActive): array
{
    return [
        '_like' => [
            'id' => $id,
            'type' => $type,
            'isActive' => $isActive,
        ],
    ];
}
