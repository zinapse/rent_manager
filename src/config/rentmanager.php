<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Rent Manager Subdomain
    |--------------------------------------------------------------------------
    |
    | This value is your subdomain for Rent Manager.
    | https://{subdomain}.api.rentmanager.com
    | Ex: https://mysubdomain.api.rentmanager.com
    */
    'subdomain' => env('RENT_MANAGER_SUBDOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | Rent Manager Username
    |--------------------------------------------------------------------------
    |
    | This value is your API username for Rent Manager.
    */
    'username' => env('RENT_MANAGER_USERNAME'),

    /*
    |--------------------------------------------------------------------------
    | Rent Manager Password
    |--------------------------------------------------------------------------
    |
    | This value is your API password for Rent Manager.
    */
    'password' => env('RENT_MANAGER_PASSWORD'),

    /*
    |--------------------------------------------------------------------------
    | Rent Manager Location ID
    |--------------------------------------------------------------------------
    |
    | This value is your API user's location ID for Rent Manager.
    */
    'location' => env('RENT_MANAGER_LOCATION'),

];