<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks\Bookmark\Rest;

use Widoz\Wp\Konomi\Rest;
use Widoz\Wp\Konomi\User;

/**
 * @internal
 */
class AddController implements Rest\Controller
{
    public static function new(
        User\UserFactory $userFactory,
        User\ItemFactory $bookmarkFactory
    ): AddController {

        return new self($userFactory, $bookmarkFactory);
    }

    final private function __construct(
        private readonly User\UserFactory $userFactory,
        private readonly User\ItemFactory $bookmarkFactory
    ) {
    }

    public function __invoke(\WP_REST_Request $request): \WP_REST_Response|\WP_Error
    {
        $bookmark = $this->bookmarkByRequest($request);

        if (!$bookmark->isValid()) {
            return $this->failedBecauseInvalidData();
        }

        return $this->userFactory->create()->saveItem($bookmark)
            ? $this->successResponse()
            : $this->failedToSaveError();
    }

    private function successResponse(): \WP_REST_Response
    {
        return new \WP_REST_Response([
            'success' => true,
            'message' => 'Bookmark saved',
        ], 201);
    }

    private function failedBecauseInvalidData(): \WP_Error
    {
        return new \WP_Error(
            'invalid_bookmark_data',
            'Invalid Bookmark data, please contact the support or try again later.',
            ['status' => 400]
        );
    }

    private function failedToSaveError(): \WP_Error
    {
        return new \WP_Error(
            'failed_to_save_bookmark',
            'Failed to save bookmark',
            ['status' => 500]
        );
    }

    private function bookmarkByRequest(\WP_REST_Request $request): User\Item
    {
        $rawBookmark = $this->ensureMeta($request->get_param('meta'));
        return $this->bookmarkFactory->create(
            $rawBookmark['id'],
            $rawBookmark['type'],
            $rawBookmark['isActive'],
            User\ItemGroup::BOOKMARK
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

        $rawBookmark = (array) ($meta['_bookmark'] ?? null);

        $id = is_int($rawBookmark['id'] ?? null) ? $rawBookmark['id'] : 0;
        $type = is_string($rawBookmark['type'] ?? null) ? $rawBookmark['type'] : '';
        $isActive = is_bool($rawBookmark['isActive'] ?? null) ? $rawBookmark['isActive'] : false;

        return [
            'id' => $id,
            'type' => $type,
            'isActive' => $isActive,
        ];
    }
}
