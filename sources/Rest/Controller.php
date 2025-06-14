<?php

declare(strict_types=1);

namespace SpaghettiDojo\Konomi\Rest;

interface Controller
{
    public function __invoke(\WP_REST_Request $request): \WP_REST_Response|\WP_Error;
}
