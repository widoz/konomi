<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks;

trait PostContextTrait
{
    private function postType(): string
    {
        return (string) get_post_type($this->postId());
    }

    private function postId(): int
    {
        return (int) get_the_ID();
    }
}
