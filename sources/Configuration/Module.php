<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Configuration;

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

    final private function __construct(private Properties $appProperties)
    {
    }

    public function services(): array
    {
        return [
        ];
    }

    // TODO Encapsulate the Settings Schema.
    public function run(ContainerInterface $container): bool
    {
        register_setting('konomi', 'konomi', [
            'type' => 'object',
            'show_in_rest' => [
                'name' => 'konomi',
                'schema' => [
                    'type' => 'object',
                    'properties' => [
                        'iconsPathUrl' => [
                            'type' => 'string',
                        ],
                    ],
                    'default' => [
                        'iconsPathUrl' => $this->appProperties->baseUrl() . 'resources/icons',
                    ],
                ],
            ],
            'description' => 'Konomi Settings',
        ]);

        return true;
    }
}
