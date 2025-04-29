<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks\Like\Rest;

use Widoz\Wp\Konomi\Rest;
use Widoz\Wp\Konomi\User;

/**
 * @internal
 */
class AddController implements Rest\Controller
{
    public static function new(
        User\UserFactory $userFactory,
        User\ItemFactory $likeFactory
    ): AddController {

        return new self($userFactory, $likeFactory);
    }

    final private function __construct(
        private readonly User\UserFactory $userFactory,
        private readonly User\ItemFactory $likeFactory
    ) {
    }

    public function __invoke(\WP_REST_Request $request): \WP_REST_Response|\WP_Error
    {
        $like = $this->likeByRequest($request);

        if (!$like->isValid()) {
            return $this->failedBecauseInvalidData();
        }

        return $this->userFactory->create()->saveLike($like)
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
        $rawLike = $this->ensureMeta($request->get_param('meta'));
        return $this->likeFactory->create(
            $rawLike['id'],
            $rawLike['type'],
            $rawLike['isActive']
        );
    }

    /**
     * @return array{id: int, type: string, isActive: bool}
     */
    private function ensureMeta(mixed $meta): array
    {
        if (!is_array($meta)) {
            return ['id' => 0, 'type' => '', 'isActive' => false];
        }

        $rawLike = (array) ($meta['_like'] ?? null);

        $id = is_int($rawLike['id'] ?? null) ? $rawLike['id'] : 0;
        $type = is_string($rawLike['type'] ?? null) ? $rawLike['type'] : '';
        $isActive = is_bool($rawLike['isActive'] ?? null) ? $rawLike['isActive'] : false;

        return [
            'id' => $id,
            'type' => $type,
            'isActive' => $isActive,
        ];
    }
}
