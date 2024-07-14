<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\ApiFetch;

use Psr\Container\ContainerInterface;
use Inpsyde\Modularity\{
    Module\ServiceModule,
    Module\ExecutableModule,
    Module\ModuleClassNameIdTrait,
    Properties\Properties
};

class Module implements ServiceModule, ExecutableModule
{
    use ModuleClassNameIdTrait;

    public static function new(Properties $appProperties): self
    {
        return new self($appProperties);
    }

    final private function __construct(readonly private Properties $appProperties)
    {
    }

    public function services(): array
    {
        return [
            'konomi.api-fetch.script-enqueue-filter' => static fn () => ScriptEnqueueFilter::new(),
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        add_action('wp_enqueue_scripts', function () use ($container): void {
            $moduleLocationPath = 'sources/ApiFetch/client/build-module';
            $baseUrl = untrailingslashit($this->appProperties->baseUrl() ?? '');
            $baseDir = untrailingslashit($this->appProperties->basePath() ?? '');

            $configuration = (array) (include "{$baseDir}/{$moduleLocationPath}/konomi-api-fetch.asset.php");

            $dependencies = $configuration['dependencies'] ?? [];
            $version = $configuration['version'] ?? $this->appProperties->version();

            wp_register_script_module(
                '@konomi/api-fetch',
                "{$baseUrl}/{$moduleLocationPath}/konomi-api-fetch.js",
                $dependencies,
                $version
            );
        });

        $container->get('konomi.api-fetch.script-enqueue-filter')->addFilter();

        return true;
    }
}
