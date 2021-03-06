
[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/support-ukraine.svg?t=1" />](https://supportukrainenow.org)

# Generate OTP for your Laravel application

[![Latest Version on Packagist](https://img.shields.io/packagist/v/morcen/laravel-otp-generator.svg?style=flat-square)](https://packagist.org/packages/morcen/laravel-otp-generator)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/morcen/laravel-otp-generator/run-tests?label=tests)](https://github.com/morcen/laravel-otp-generator/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/morcen/laravel-otp-generator/Check%20&%20fix%20styling?label=code%20style)](https://github.com/morcen/laravel-otp-generator/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/morcen/laravel-otp-generator.svg?style=flat-square)](https://packagist.org/packages/morcen/laravel-otp-generator)

This package requires Laravel >= 8.x.

## Installation

1. Install the package via composer:
    ```bash
    composer require morcen/laravel-otp-generator
    ```

1. Publish the config file with:
    ```bash
    php artisan vendor:publish --tag="otp-generator-config"
    ```

    Open `config/otp.php` and update the following:
    - `identifier` - this is what this package will use to query for the OTP record in the database
    - `set` - this defines the characters that will be used for the code. Possible values are:
        - `numbers` - from zero to nine (0 to 9)
        - `lowercase` - English alphabet from `a` to `z`
        - `uppercase` - English alphabet from `A` to `Z`
        - `others`- defaults to empty string. You can put any other characters you wish to see as part of the OTP code.
        - `all` - uses all letters and numbers, including the characters in `others` option. This is the defualt option.
    - `lifetime` - defines how long the OTP will be valid for. Default is `15` minutes.
    - `length` - defines the default length of the OTP. Though this can be simply overriden whenever the `generate` or `generateFor` methods are called. Default length is `4`.
    - `encrypt` - if set to `true`, it will have additional property `hash` in the returned object. If used with `generateFor()` method, the value that will be saved in the database will be the encrypted value. Available since `v1.1.0`.
    - `alg` - This will be the hashing algorithm used for the OTP code. This will only take effect if `encrypt` is set to `true`. Valid options are `sha1` and `md5`. Available since `v1.1.0`.
    
    
1. Publish the migrations with:
    ```bash
    php artisan vendor:publish --tag="otp-generator-migrations"
    ```
    and update it to include the `identifier` field mentioned in the previous step. For example, if you have
    ```php
    'identifier' => 'email'
    ```
    in your `config/otp.php`, you have to add this line in the migration file created:
    ```php
    Schema::create('otps', function (Blueprint $table) {
        $table->id();
        $table->string('email');  // <-- this is the line added to match the `identifier`
        $table->string('code');
        $table->unsignedInteger('expiration');
        $table->timestamp('created_at');
    });
    ```
    
1. Run the migrations with:
   ```bash 
    php artisan migrate
    ```

## Usage

To generate an OTP that can be validated afterwards, use the method `generatedFor()`. It accepts two parameters:

- `$identifierValue` (required) - the value that will be matched against the `identifier`
- `$length` (optional) - the length of the code to use. If this is not provided, it will use the default `length` option set in `config/otp.php`.
    
For example, we want to create an OTP for the email `abcd@example.com`:
```php
use Morcen\LaravelOtpGenerator\Facades\Otp;

$otp = Otp::generateFor('abcd@example.com');
```

`$otp` receives back the OTP object*, e.g.
```json
{
    "email": "abcd@example.com",
    "code": "028988",
    "hash": "6120a26f84406f452c1b27509505093ba4aa263d",
    "expiration": 1647363156
}
```
And then to validate, use the method `validateFor`, accepting the `$identifierValue` and the OTP as parameters and returns `bool`:
```php
Otp::validateFor('abcd@example.com', '028988'); // returns `true`
```

**NOTE: `hash` property only appears in the OTP object if `encrypt` is set to `true`.*

---

To generate and receive an OTP code only:
```php
use Morcen\LaravelOtpGenerator\Facades\Otp;

$otp = Otp::generate();
```

`$otp` receives back just the OTP object like this:
```json
{
    "code": "234198",
    "hash": "858edae3093a5b6f5b7812cff2e2e369da0a2290"
}
```
**NOTE: `hash` property only appears in the OTP object if `encrypt` is set to `true`.*

---
To override the default `length` of the OTP, pass the length as a parameter. For example, to generate an OTP that is 10 characters long:
```php
$otp = Otp::generateFor('abcd@example.com', 10); 
```
or
```php
$otp = Otp::generate(10); 
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Morcen](https://github.com/morcen)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
