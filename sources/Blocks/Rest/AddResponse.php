<?php

declare(strict_types=1);

namespace SpaghettiDojo\Konomi\Blocks\Rest;

use SpaghettiDojo\Konomi\User;

class AddResponse
{
    public static function new(
        User\ItemGroup $group,
        string $successMessage,
        string $invalidRequestMessage,
        string $failedToSaveMessage
    ): AddResponse {

        return new self(
            $group,
            $successMessage,
            $invalidRequestMessage,
            $failedToSaveMessage
        );
    }

    final private function __construct(
        private readonly User\ItemGroup $group,
        private readonly string $successMessage,
        private readonly string $invalidRequestMessage,
        private readonly string $failedToSaveMessage,
    ) {
    }

    public function successResponse(): \WP_REST_Response
    {
        return new \WP_REST_Response([
            'success' => true,
            'message' => $this->successMessage,
        ], 201);
    }

    public function failedBecauseInvalidData(): \WP_Error
    {
        return new \WP_Error(
            "invalid_{$this->group->value}_data",
            $this->invalidRequestMessage,
            ['status' => 400]
        );
    }

    public function failedToSaveError(): \WP_Error
    {
        return new \WP_Error(
            "failed_to_save_{$this->group->value}",
            $this->failedToSaveMessage,
            ['status' => 500]
        );
    }
}
