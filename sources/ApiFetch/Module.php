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
use Widoz\Wp\Konomi\Utils;

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
        return [];
    }

    public function run(ContainerInterface $container) : bool
    {
        add_action('wp_enqueue_scripts', function () use ($container): void {
            $service = $container->get('konomi.configuration');
            $moduleLocationPath = 'sources/ApiFetch/client/build-module';
            $baseUrl = untrailingslashit($this->appProperties->baseUrl() ?? '');
            $baseDir = untrailingslashit($this->appProperties->basePath() ?? '');

            $configuration = (array)(include "{$baseDir}/{$moduleLocationPath}/konomi-api-fetch.asset.php");

            wp_register_script_module(
                '@konomi/api-fetch',
                "{$baseUrl}/{$moduleLocationPath}/konomi-api-fetch.js",
                [],
                $configuration['version']
            );
        });

            Utils\ConditionalRemovableHook::filter(
                'wp_inline_script_attributes',
                static function(
                    Utils\ConditionalRemovableHook $that,
                    array $attributes,
                    string $data
                ) {
                   if (str_contains($data, '"@konomi\/api-fetch"')) {
                       wp_enqueue_script('wp-api-fetch');
                        $that->remove();
                   }

                   return $attributes;
            },
            10,
            2
        );

        return true;
    }
}
