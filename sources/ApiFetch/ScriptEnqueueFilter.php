<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\ApiFetch;

use Widoz\Wp\Konomi\Utils;

/**
 * @internal
 */
class ScriptEnqueueFilter
{
    public static function new(): ScriptEnqueueFilter
    {
        return new self();
    }

    final private function __construct()
    {
    }

    public function addFilter(): void
    {
        Utils\ConditionalRemovableImportMapAwareHook::do(
            static function (array $attributes, string $data): bool {
                return str_contains($data, '"@konomi\/api-fetch"');
            },
            static function (): void {
                wp_enqueue_script('wp-api-fetch');
            }
        );
    }
}
