<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Configuration;

use Inpsyde\Modularity;

class Configuration
{
    public static function new(Modularity\Properties\Properties $properties): Configuration
    {
        return new self($properties);
    }

    final private function __construct(private Modularity\Properties\Properties $properties)
    {
    }

    public function iconsPathUrl(): string
    {
        $baseUrl = untrailingslashit($this->properties->baseUrl() ?? '');
        return "{$baseUrl}/resources/icons";
    }

    public function inlineScript(): string
    {
        return "window.konomi = " . $this->serialize() . ";";
    }

    private function serialize(): string
    {
        return wp_json_encode(
            [
                'iconsPathUrl' => $this->iconsPathUrl(),
            ]
        );
    }
}
