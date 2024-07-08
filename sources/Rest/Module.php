<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Rest;

use Psr\Container\ContainerInterface;
use Inpsyde\Modularity\{
    Module\ServiceModule,
    Module\ExecutableModule,
    Module\ModuleClassNameIdTrait
};

class Module implements ServiceModule, ExecutableModule
{
    use ModuleClassNameIdTrait;

    public static function new(): self
    {
        return new self();
    }

    final private function __construct()
    {
    }

    public function services(): array
    {
        return [];
    }

    public function run(ContainerInterface $container) : bool
    {
        // TODO Switch to GraphQL or stay with Rest API?
        add_action(
            'rest_api_init',
            static function () use ($container) {
                register_rest_route(
                    'konomi/v1',
                    '/user-like',
                    [
                        'methods' => \WP_REST_Server::CREATABLE,
                        'callback' => static function ($request) use ($container): \WP_REST_Response|\WP_Error {
                            $meta = $request->get_param('meta')['_likes'] ?? [];
                            if (!$meta) {
                                return new \WP_Error(
                                    'no_likes',
                                    'No likes provided',
                                    ['status' => 400]
                                );
                            }

                            $result = $container->get('konomi.user')->saveLike(
                                $container->get('konomi.likes.factory')->create(
                                    (int) $meta['id'],
                                    $meta['type'],
                                    $meta['isActive']
                                )
                            );

                            if ($result === false) {
                                return new \WP_Error(
                                    'failed_to_save_like',
                                    'Failed to save like',
                                    ['status' => 500]
                                );
                            }

                            return new \WP_REST_Response([
                                'success' => true,
                                'message' => 'Like saved',
                            ], 201);
                        },
                        'permission_callback' => '__return_true',
                    ]
                );
            }
        );

        return true;
    }
}
