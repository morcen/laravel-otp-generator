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
    'length' => 4,

    /*
    |--------------------------------------------------------------------------
    | Encrypt OTP
    |--------------------------------------------------------------------------
    |
    | If set to true, OTP object will be returned with additional property
    | `hash` for the method `generate()`. If used with `generateFor()`, the
    | value that will be saved in the database will the encrypted value.
    |
    | Available since v1.1.0
    |
    */
    'encrypt' => false,

    /*
    |--------------------------------------------------------------------------
    | Hashing algorithm
    |--------------------------------------------------------------------------
    |
    | This will be the hashing algorithm used for the OTP code. This will only
    |  take effect if `encrypt` is set to `true`.
    |
    | Valid options are 'sha1' and 'md5'
    |
    | Available since v1.1.0
    |
    */
    'alg' => 'sha1',
];
