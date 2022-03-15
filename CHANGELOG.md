# Changelog

All notable changes to `laravel-otp-generator` will be documented in this file.

## v1.1.0 - 2022-03-15

## What's Changed

- Add basic tests by @morcen in https://github.com/morcen/laravel-otp-generator/pull/3
- Option to allow encryption of OTP by @morcen in https://github.com/morcen/laravel-otp-generator/pull/8

## IMPORTANT:

### Breaking change (Moderate)

1. The response of `generate()` method is changed from `string` to `object`. To upgrade, change implementation to use `code` property.
2. Before: `$otp = Otp::generate();`
3. After: `$otp = Otp::generate()->code;`
4. 
5. The response of `generateFor()` method is changed from `Otp` model to `object`. To upgrade, just make sure that `created_at` and `id` properties of the `Otp::generateFor()` response are not used.
6. 

**Full Changelog**: https://github.com/morcen/laravel-otp-generator/compare/v1.0.3...v1.1.0

## v1.0.3 - 2022-03-14

Update README.md

## v1.0.1 - 2022-03-14

- update missing core files

## v1.0.0 - 2022-03-14

Initial release:

- standalone OTP generator
- generate OTP attached to an identifier (like `email`) and a validator
- customisable character set, OTP length, and expiration

## 1.0.0 - 202X-XX-XX

- initial release
