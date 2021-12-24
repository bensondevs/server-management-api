<?php 

return [

	/*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL of the SEB API Endpoint service.
    | This configuration will be automatically set the url for testing or production.
    |
    */
    'base_url' => (env('APP_ENV', 'local') !== 'production') ? 
    	env('SEB_API_URL_TEST', 'https://igw-demo.every-pay.com/api/v3') : 
    	env('SEB_API_URL_PRODUCTION', 'https://payment.ecommerce.sebgroup.com/api/v3'),

   	/*
    |--------------------------------------------------------------------------
    | API Username
    |--------------------------------------------------------------------------
    |
    | The username for the API Parameter request. This must be the same as basic token
    | value of username
    |
    */
    'api_username' => env('SEB_USERNAME'),

    /*
    |--------------------------------------------------------------------------
    | SEB Account Name
    |--------------------------------------------------------------------------
    |
    | The username for the API Parameter request. This must be the same as basic token
    | value of username
    |
    */
    'account_name' => env('SEB_ACCOUNT_NAME'),

    /*
    |--------------------------------------------------------------------------
    | SEB Customer URL
    |--------------------------------------------------------------------------
    |
    | This url will be used to redirect the user after finishing the payment
    | process in the vendor payment page. This url CANNOT use `localhost` or
    | another invalid url.
    |
    */
    'customer_url' => env('SEB_CUSTOMER_URL'),

    /*
    |--------------------------------------------------------------------------
    | Basic Authentication Token
    |--------------------------------------------------------------------------
    |
    | The basic authentication token.
    |
    */
    'basic_token' => env('SEB_USERNAME') . ':' . env('SEB_SECRET'),
];