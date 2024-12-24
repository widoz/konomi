<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks\Like\Rest;

use Widoz\Wp\Konomi\Rest;
use Widoz\Wp\Konomi\User;

class AddController implements Rest\Controller
{
    public static function new(
        User\User $user,
        User\ItemFactory $likeFactory
    ): AddController {

        return new self($user, $likeFactory);
    }

    final private function __construct(
        private readonly User\User $user,
        private readonly User\ItemFactory $likeFactory
    ) {
    }

    public function __invoke(\WP_REST_Request $request): \WP_REST_Response|\WP_Error
    {
        $like = $this->likeByRequest($request);

        if (!$like->isValid()) {
            return $this->failedBecauseInvalidData();
        }

        return $this->user->saveLike($like)
            ? $this->successResponse()
            : $this->failedToSaveError();
    }

    private function successResponse(): \WP_REST_Response
    {
        return new \WP_REST_Response([
            'success' => true,
            'message' => 'Like saved',
        ], 201);
    }

    private function failedBecauseInvalidData(): \WP_Error
    {
        return new \WP_Error(
            'invalid_like_data',
            'Invalid Like Data, please contact the support or try again later.',
            ['status' => 400]
        );
    }

    private function failedToSaveError(): \WP_Error
    {
        return new \WP_Error(
            'failed_to_save_like',
            'Failed to save like',
            ['status' => 500]
        );
    }

    private function likeByRequest(\WP_REST_Request $request): User\Item
    {
        $rawLike = $this->ensureMeta((array) $request->get_param('meta'));
        return $this->likeFactory->create(
            $rawLike['id'],
            $rawLike['type'],
            $rawLike['isActive']
        );
    }

    /**
     * @return array{id: int, type: string, isActive: bool}
     */
    private function ensureMeta(?array $meta): array
    {
        $meta = (array) $meta;
        $like = (array) ($meta['_like'] ?? null);

        return [
            'id' => (int) ($like['id'] ?? null),
            'type' => (string) ($like['type'] ?? null),
            'isActive' => (bool) ($like['isActive'] ?? null),
        ];
    }
}
