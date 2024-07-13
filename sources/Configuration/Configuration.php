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

    final private function __construct(readonly private Modularity\Properties\Properties $properties)
    {
    }

    public function iconsPathUrl(): string
    {
        $baseUrl = untrailingslashit($this->properties->baseUrl() ?? '');
        return "{$baseUrl}/sources/Icons/icons";
    }

    public function iconsPath(): string
    {
        $basePath = untrailingslashit($this->properties->basePath() ?? '');
        return "{$basePath}/sources/Icons/icons";
    }

    public function serialize(): string
    {
        return (string) wp_json_encode(
            [
                'iconsPathUrl' => $this->iconsPathUrl(),
                'iconsPath' => $this->iconsPath(),
            ]
        );
    }
}
