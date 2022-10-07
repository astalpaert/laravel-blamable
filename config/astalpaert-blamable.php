<?php

return [
    'user' => [
        /*
        |--------------------------------------------------------------------------
        | User default name
        |--------------------------------------------------------------------------
        |
        | This value is used when no authenticated User is found.
        | Expected: string
        |
        */

        'default' => 'SYSTEM',

        /*
        |--------------------------------------------------------------------------
        | User auth attribute name
        |--------------------------------------------------------------------------
        |
        | Here you may specify which attribute should be used
        | to resolve the name of the authenticated User.
        | Expected: string
        |
        */

        'attribute_name' => 'name',
    ],
];
