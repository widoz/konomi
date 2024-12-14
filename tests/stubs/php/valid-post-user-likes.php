<?php

declare(strict_types=1);

$data = [
    '_konomi_likes' => [
        10 => [
            100 => [
                [10, 'post'],
            ],
            21 => [
                [10, 'product'],
            ],
            33 => [
                [10, 'video'],
            ],
            45 => [
                [10, 'page'],
            ],
            53 => [
                [10, 'post'],
            ],
            6 => [
                [10, 'post'],
            ],
            79 => [
                [10, 'page'],
            ],
            83 => [
                [10, 'page'],
            ],
            92 => [
                [10, 'post'],
            ],
            1000 => [
                [10, 'post'],
            ],
        ]
    ]
];

return static function(int $entityId, string $key, bool $single) use ($data): array {
    return $data[$key][$entityId] ?? [];
};
