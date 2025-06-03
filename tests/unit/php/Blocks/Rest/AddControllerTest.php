<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Unit\Blocks\Rest;

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
    /** @var Mockery\MockInterface&User\UserFactory $userFactory */
    $this->userFactory = Mockery::mock(User\UserFactory::class, [
        'create' => $this->user,
    ]);
    /** @var Mockery\MockInterface&User\ItemFactory $itemFactory */
    $this->itemFactory = Mockery::mock(User\ItemFactory::class);
    /** @var Mockery\MockInterface&User\Item $item */
    $this->item = Mockery::mock(User\Item::class);
    /** @var Mockery\MockInterface&\WP_REST_Request $request */
    $this->request = Mockery::mock(\WP_REST_Request::class);
    /** @var Mockery\MockInterface&Blocks\Rest\AddResponse $addResponse */
    $this->addResponse = Mockery::mock(Blocks\Rest\AddResponse::class, [
        'successResponse' => new \WP_REST_Response(['success' => true, 'message' => 'Reaction saved'], 201),
        'failedToSaveError' => new \WP_Error('failed_to_save_reaction', 'Failed to save reaction', ['status' => 500]),
        'failedBecauseInvalidData' => new \WP_Error('invalid_reaction_data', 'Invalid Reaction Data', ['status' => 400]),
    ]);
});

describe('__invoke', function (): void {
    it('Successfully save an Item', function (): void {
        $id = 1;
        $type = 'post';
        $isActive = true;
        $rawRequestData = makeRequestData($id, $type, $isActive);

        $this->request->shouldReceive('get_param')->with('meta')->andReturn($rawRequestData);
        $this->itemFactory->shouldReceive('create')->with($id, $type, $isActive, User\ItemGroup::REACTION)->andReturn($this->item);
        $this->item->shouldReceive('isValid')->andReturn(true);
        $this->user->shouldReceive('saveItem')->with($this->item)->andReturn(true);

        $controller = Blocks\Rest\AddController::new(
            $this->userFactory,
            $this->itemFactory,
            User\ItemGroup::REACTION,
            $this->addResponse
        );
        $result = $controller($this->request);

        expect($result->get_status())->toBe(201)
            ->and($result->get_data()['success'])->toBe(true)
            ->and($result->get_data()['message'])->toContain('Reaction saved');
    });

    /**
     * In this test the request values do not matter much, but the
     * `Item::isValid` is what makes the difference.
     */
    it('Fails to save the Like because of invalid data', function (): void {
        $id = 1;
        $type = 'post';
        $isActive = true;
        $rawRequestData = makeRequestData($id, $type, $isActive);

        $this->request->shouldReceive('get_param')->with('meta')->andReturn($rawRequestData);
        $this->itemFactory->shouldReceive('create')->with($id, $type, $isActive, User\ItemGroup::REACTION)->andReturn($this->item);
        $this->item->shouldReceive('isValid')->andReturn(false);

        $controller = Blocks\Rest\AddController::new(
            $this->userFactory,
            $this->itemFactory,
            User\ItemGroup::REACTION,
            $this->addResponse
        );
        $result = $controller($this->request);

        expect($result->get_error_code())->toContain('invalid_reaction_data')
            ->and($result->get_error_message())->toContain('Invalid Reaction Data')
            ->and($result->get_error_data()['status'])->toBe(400);
    });

    it('Fails to save the Item because meta parameter is not an array', function (): void {
        $this->request->shouldReceive('get_param')->with('meta')->andReturn('NOT AN ARRAY');
        $this->itemFactory->shouldReceive('create')->with(0, '', false, User\ItemGroup::REACTION)->andReturn($this->item);
        $this->item->shouldReceive('isValid')->andReturn(false);

        $controller = Blocks\Rest\AddController::new(
            $this->userFactory,
            $this->itemFactory,
            User\ItemGroup::REACTION,
            $this->addResponse
        );
        $result = $controller($this->request);

        expect($result->get_error_code())->toContain('invalid_reaction_data')
            ->and($result->get_error_message())->toContain('Invalid Reaction Data')
            ->and($result->get_error_data()['status'])->toBe(400);
    });

    /**
     * In this test the Request values do not matter much but the
     * `User::saveItem`.
     */
    it('Fails to save the Like', function (): void {
        $id = 1;
        $type = 'post';
        $isActive = true;
        $rawRequestData = makeRequestData($id, $type, $isActive);

        $this->request->shouldReceive('get_param')->with('meta')->andReturn($rawRequestData);
        $this->itemFactory->shouldReceive('create')->with($id, $type, $isActive, User\ItemGroup::REACTION)->andReturn($this->item);
        $this->item->shouldReceive('isValid')->andReturn(true);
        $this->user->shouldReceive('saveItem')->with($this->item)->andReturn(false);

        $controller = Blocks\Rest\AddController::new(
            $this->userFactory,
            $this->itemFactory,
            User\ItemGroup::REACTION,
            $this->addResponse
        );
        $result = $controller($this->request);

        expect($result->get_error_code())->toContain('failed_to_save_reaction');
        expect($result->get_error_message())->toContain('Failed to save reaction');
        expect($result->get_error_data()['status'])->toBe(500);
    });
});

function makeRequestData(int $id, string $type, bool $isActive): array
{
    return [
        '_' . User\ItemGroup::REACTION->value => [
            'id' => $id,
            'type' => $type,
            'isActive' => $isActive,
        ],
    ];
}
