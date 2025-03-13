<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks;

/**
 * @internal
 */
class TemplateRender
{
    public static function new(string $templateRootDir, bool $isDebugMode): TemplateRender
    {
        return new self($templateRootDir, $isDebugMode);
    }

    final private function __construct(
        readonly private string $templateRootDir,
        readonly private bool $isDebugMode
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public function render(string $path, array $data): string
    {
        $isDebugMode = $this->isDebugMode;

        $path = wp_normalize_path($this->templateRootDir . '/' . $path);
        $path = untrailingslashit($path);
        $path = self::ensureExtension($path);

        $renderer = function (string $path, array $data): string {
            ob_start();
            $data = (array) apply_filters('konomi.template.render.data', $data, $path);
            $path = (string) apply_filters('konomi.template.render.path', $path, $data);

            if (!is_readable($path) && $this->isDebugMode) {
                // @phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
                throw new \RuntimeException("Konomi, template file not found: $path");
            }

            $path and include realpath($path);
            return (string) ob_get_clean();
        };

        try {
            $output = $renderer($path, $data);
        } catch (\Throwable $error) {
            ob_end_clean();
            if ($isDebugMode) {
                throw $error;
            }
            $output = '';
        }

        return $output;
    }

    private static function ensureExtension(string $path): string
    {
        if (pathinfo($path, PATHINFO_EXTENSION) === '') {
            return $path . '.php';
        }

        return $path;
    }
}
