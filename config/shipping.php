<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Shipping Costs
    |--------------------------------------------------------------------------
    |
    | Define shipping costs for different shipping methods. Values are in IDR.
    |
    */

    'costs' => [
        'regular' => 15000,   // 3-5 days
        'express' => 30000,   // 1-2 days
        'same_day' => 50000,  // Same day delivery
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Shipping Method
    |--------------------------------------------------------------------------
    |
    | The default shipping method to use when not specified.
    |
    */

    'default_method' => 'regular',
];
