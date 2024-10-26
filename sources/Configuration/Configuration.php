<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Configuration;

use Inpsyde\Modularity;

class Configuration
{
    public static function new(
        Modularity\Properties\Properties $properties,
        string $relativeIconsPath
    ): Configuration {

        return new self($properties, $relativeIconsPath);
    }

    final private function __construct(
        readonly private Modularity\Properties\Properties $properties,
        readonly private string $relativeIconsPath
    ) {
    }

    public function iconsPathUrl(): string
    {
        return $this->buildIconsPath($this->properties->baseUrl() ?? '');
    }

    public function iconsPath(): string
    {
        return $this->buildIconsPath($this->properties->basePath() ?? '');
    }

    public function serialize(): string
    {
        return (string) wp_json_encode(
            [
                'iconsPathUrl' => $this->iconsPathUrl(),
                'iconsPath' => $this->iconsPath(),
                'isDebugMode' => $this->properties->isDebug(),
            ]
        );
    }

    private function buildIconsPath(string $base): string
    {
        $relativeIconsPath = untrailingslashit($this->relativeIconsPath);
        $basePath = untrailingslashit($base);
        return "{$basePath}/{$relativeIconsPath}";
    }
}
