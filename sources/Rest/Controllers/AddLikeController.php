<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Rest\Controllers;

use Widoz\Wp\Konomi\Rest;
use Widoz\Wp\Konomi\User;

class AddLikeController implements Rest\Controller
{
    public static function new(
        User\User $user,
        User\Like\Factory $likeFactory
    ): AddLikeController {

        return new self($user, $likeFactory);
    }

    final private function __construct(
        private readonly User\User $user,
        private readonly User\Like\Factory $likeFactory
    ) {
    }

    public function __invoke(\WP_REST_Request $request): \WP_REST_Response|\WP_Error
    {
        $meta = $request->get_param('meta')['_like'] ?? [];
        if (!$meta) {
            return new \WP_Error(
                'no_likes',
                'No likes provided',
                ['status' => 400]
            );
        }

        $result = $this->user->saveLike(
            $this->likeFactory->create(
                (int) $meta['id'],
                $meta['type'],
                $meta['isActive']
            )
        );

        if ($result === false) {
            return new \WP_Error(
                'failed_to_save_like',
                'Failed to save like',
                ['status' => 500]
            );
        }

        return new \WP_REST_Response([
            'success' => true,
            'message' => 'Like saved',
        ], 201);
    }
}
