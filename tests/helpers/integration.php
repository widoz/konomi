<?php

declare(strict_types=1);

require_once __DIR__ . '/functions.php';

function includeValidPostUserLikes(): array
{
    return include stubsDirectory() . '/php/valid-post-user-reactions.php';
}

function includeValidUsersLikes(): array
{
    return include stubsDirectory() . '/php/valid-users-likes.php';
}

/**
 * @param array<int, array<string, mixed>> $data
 * @return array{0: callable, 1: callable, 2: callable, 3: callable}
 */
function setupUserMetaStorage(array &$data): array
{
    $stubsCounter = [
        'get_user_meta' => 0,
        'update_user_meta' => 0,
    ];

    return [
        static function () use (&$stubsCounter): array {
            return $stubsCounter;
        },
        static function (int $entityId, string $key) use (&$data, &$stubsCounter): array {
            $stubsCounter['get_user_meta']++;
            return (array)($data[$entityId][$key] ?? null);
        },
        static function (int $entityId, string $key, array $newData) use (&$data, &$stubsCounter): bool {
            $stubsCounter['update_user_meta']++;
            $data[$entityId][$key] = $newData;
            return true;
        },
    ];
}

/**
 * @return array{0: callable, 1: callable, 2: callable, 3: callable}
 */
function setupPostMetaStorage(array &$data): array
{
    $stubsCounter = [
        'get_post_meta' => 0,
        'update_post_meta' => 0,
    ];

    return [
        static function () use (&$stubsCounter): array {
            return $stubsCounter;
        },
        static function (int $entityId, string $key, bool $single) use (&$data, &$stubsCounter): array {
            $stubsCounter['get_post_meta']++;
            return (array)($data[$entityId][$key] ?? null);
        },
        static function (int $entityId, string $key, array $newData) use (&$data, &$stubsCounter): bool {
            $stubsCounter['update_post_meta']++;
            $data[$entityId][$key] = $newData;
            return true;
        },
    ];
}
