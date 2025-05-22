<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks\Rest;

use Widoz\Wp\Konomi\Rest\Controller;
use Widoz\Wp\Konomi\User;

/**
 * @internal
 */
class AddController implements Controller
{
    public static function new(
        User\UserFactory $userFactory,
        User\ItemFactory $itemFactory,
        User\ItemGroup $itemGroup,
        AddResponse $addResponse,
    ): AddController {

        return new self($userFactory, $itemFactory, $itemGroup, $addResponse);
    }

    final private function __construct(
        private readonly User\UserFactory $userFactory,
        private readonly User\ItemFactory $itemFactory,
        private readonly User\ItemGroup $itemGroup,
        private readonly AddResponse $addResponse
    ) {
    }

    public function __invoke(\WP_REST_Request $request): \WP_REST_Response|\WP_Error
    {
        $item = $this->itemByRequest($request);

        if (!$item->isValid()) {
            return $this->addResponse->failedBecauseInvalidData();
        }

        return $this->userFactory->create()->saveItem($item)
            ? $this->addResponse->successResponse()
            : $this->addResponse->failedToSaveError();
    }

    private function itemByRequest(\WP_REST_Request $request): User\Item
    {
        $rawBookmark = $this->ensureMeta($request->get_param('meta'));
        return $this->itemFactory->create(
            $rawBookmark['id'],
            $rawBookmark['type'],
            $rawBookmark['isActive'],
            $this->itemGroup
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

        $rawItem = (array) ($meta[$this->metaKey()] ?? null);

        $id = is_int($rawItem['id'] ?? null) ? $rawItem['id'] : 0;
        $type = is_string($rawItem['type'] ?? null) ? $rawItem['type'] : '';
        $isActive = is_bool($rawItem['isActive'] ?? null) ? $rawItem['isActive'] : false;

        return [
            'id' => $id,
            'type' => $type,
            'isActive' => $isActive,
        ];
    }

    private function metaKey(): string
    {
        return '_' . $this->itemGroup->value;
    }
}
