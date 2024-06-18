<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\User\Likes;

use Widoz\Wp\Konomi\User;

class Like implements User\Item
{
    public static function new(int $id, string $type): ?Like
    {
        return new self($id, $type);
    }

    final private function __construct(private readonly int $id, private readonly string $type) {}

    public function id(): int
    {
        return $this->id;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function render(): string
    {
        return '<p>like</p>';
    }
}
