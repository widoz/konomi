<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Rest\Like;

use Widoz\Wp\Konomi\Rest;
use Widoz\Wp\Konomi\User;

class AddController implements Rest\Controller
{
    public static function new(
        User\User $user,
        User\Like\Factory $likeFactory
    ): AddController {

        return new self($user, $likeFactory);
    }

    final private function __construct(
        private readonly User\User $user,
        private readonly User\Like\Factory $likeFactory
    ) {
    }

    public function __invoke(\WP_REST_Request $request): \WP_REST_Response|\WP_Error
    {
        $like = $this->likeByRequest($request);
return $this->failedToSaveError();
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

    private function likeByRequest(\WP_REST_Request $request): User\Like\Like
    {
        $rawLike = $this->ensureMeta($request->get_param('meta'));
        return $this->likeFactory->create(
            (int) $rawLike['id'],
            $rawLike['type'],
            $rawLike['isActive']
        );
    }

    private function ensureMeta(array $meta): array
    {
        $default = [
            'id' => 0,
            'type' => '',
            'isActive' => '',
        ];

        if (!array_key_exists('_like', $meta)) {
            return $default;
        }

        return wp_parse_args($meta['_like'], $default);
    }
}
