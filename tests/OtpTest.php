<?php

use Morcen\LaravelOtpGenerator\Facades\Otp;

it('can generate OTP', function () {
    expect(Otp::generate())->toBeObject();
});

it('can generate OTP of custom length', function () {
    $length = mt_rand(10, 20);
    expect(strlen(Otp::generate($length)->code))->toBe($length);
});

it('can generate OTP with numbers only', function () {
    config()->set('otp.set', 'numbers');
    expect(Otp::generate()->code)->toBeNumeric();
});

it('can generate encrypted OTP', function () {
    config()->set('otp.encrypt', true);
    expect(Otp::generate()->hash)->toBeString() ;
});

it('can generate encrypted OTP using sha1', function () {
    config()->set('otp.encrypt', true);
    config()->set('otp.alg', 'sha1');
    expect(strlen(Otp::generate()->hash))->toBe(40) ;
});

it('can generate encrypted OTP using md5', function () {
    config()->set('otp.encrypt', true);
    config()->set('otp.alg', 'md5');
    expect(strlen(Otp::generate()->hash))->toBe(32) ;
});

