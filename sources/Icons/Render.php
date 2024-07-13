<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Icons;

use Widoz\Wp\Konomi\Configuration;

class Render
{
    private static $cache = [];

    public static function new(Configuration\Configuration $configuration): Render
    {
        return new self($configuration);
    }

    final private function __construct(readonly private Configuration\Configuration $configuration)
    {
    }

    public function render(string $name): string
    {
        if (self::$cache[$name] ?? false) {
            return self::$cache[$name];
        }

        self::$cache[$name] = (string) file_get_contents("{$this->configuration->iconsPath()}/{$name}.svg");

        return self::$cache[$name];
    }
}
