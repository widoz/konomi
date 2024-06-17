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

    final private function __construct(readonly private Configuration\Configuration $configuration) {}

    public function render(string $name): string
    {
        if (self::$cache[$name] ?? false) {
            return self::$cache[$name];
        }

        ob_start();
        include "{$this->configuration->iconsPath()}/{$name}.svg";
        self::$cache[$name] = (string)ob_get_clean();

        return self::$cache[$name];
     }
}
