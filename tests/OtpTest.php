<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Morcen\LaravelOtpGenerator\Facades\Otp;

uses(RefreshDatabase::class);

it('can generate OTP', function () {
    expect(Otp::generate())->toBeString();
});

it('can generate OTP of custom length', function () {
    $length = mt_rand(10, 20);
    expect(strlen(Otp::generate($length)))->toBe($length);
});

it('can generate OTP with numbers only', function () {
    config()->set('otp.set', 'numbers');
    expect(Otp::generate())->toBeNumeric();
});
