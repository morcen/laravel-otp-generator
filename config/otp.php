<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OTP Generator available sets
    |--------------------------------------------------------------------------
    |
    | These are the
    */
    'numbers' => implode(null, range(0, 9)),
    'lowercase' => implode(null, range('a', 'z')),
    'uppercase' => implode(null, range('A', 'Z')),
    'others' => '',

    /*
    |--------------------------------------------------------------------------
    | OTP Generator default set
    |--------------------------------------------------------------------------
    |
    | Define which set you want to include when generating the OTP.
    | This can be a string or array of valid sets.
    |
    | Example: "all" or ["numbers", "lower"]
    |
    */
    'set' => 'all',

    /*
    |--------------------------------------------------------------------------
    | OTP Generator identifier field
    |--------------------------------------------------------------------------
    |
    | Here you can declare what field will be considered as "identifier", which
    | will be used to query a record in the OTP table. Make sure to match it with
    | the field that was added in your migration file.
    |
    | Example: "email" or "user_id"
    |
    */
    'identifier' => '',

    /*
    |--------------------------------------------------------------------------
    | OTP lifetime
    |--------------------------------------------------------------------------
    |
    | Defines how long the OTP will be considered as valid. This is measured in
    | minutes.
    |
    */
    'lifetime' => 15,

    /*
    |--------------------------------------------------------------------------
    | OTP length
    |--------------------------------------------------------------------------
    |
    | Defines the default length of the OTP. Can be overridden by passing an
    | integer when `generate()` method is called. Example:
    | `Otp::generate(6)` to generate 6-character OTP
    |
    */
    'length' => 4
];
