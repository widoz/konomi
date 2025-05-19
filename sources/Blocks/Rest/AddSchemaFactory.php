<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks\Rest;

use Widoz\Wp\Konomi\Rest\Schema;

/**
 * @internal
 */
class AddSchemaFactory
{
    public static function new(): AddSchemaFactory
    {
        return new self();
    }

    final private function __construct() {}

    public function create(string $title): Schema
    {
        return AddSchema::new($title);
    }
}
