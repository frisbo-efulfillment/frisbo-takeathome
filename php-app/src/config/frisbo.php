<?php

return [
    'get_organizations' => 'https://api.frisbo.ro/v1/organizations',
    'get_orders' => 'https://api.frisbo.ro/v1/organizations/:organizationId/orders',
    'login' => 'http://api.frisbo.ro/v1/auth/login',
    'email' => getenv('FRISBO_EMAIL'),
    'password' => getenv('FRISBO_PASSWORD'),
];
