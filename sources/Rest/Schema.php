<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Rest;

/**
 * @api
 */
interface Schema
{
    /**
     * @return array<mixed>
     */
    public function toArray(): array;
}
