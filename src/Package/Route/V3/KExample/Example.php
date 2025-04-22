<?php

// Web route
$manager->addRoute('/web-path', function() {
    echo 'Web route response';
});

// API route
$manager->addRoute('/api-path', function() {
    return new WP_REST_Response(['data' => 'API response']);
}, ['GET'], 'api');