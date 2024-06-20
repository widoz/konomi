<?php
declare(strict_types=1);

namespace Widoz\Wp\Konomi\User\Meta;

interface Read
{
    /**
     * @return array<array<non-negative-int, string>>
     */
    public function read(): array;
}
