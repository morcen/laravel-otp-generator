<?php

use Morcen\LaravelOtpGenerator\Exceptions\InvalidIdentifierException;
use Morcen\LaravelOtpGenerator\Exceptions\InvalidOtpHashAlgorithm;
use Morcen\LaravelOtpGenerator\Exceptions\InvalidOtpLength;
use Morcen\LaravelOtpGenerator\Exceptions\InvalidSetException;
use Morcen\LaravelOtpGenerator\Facades\Otp;

it('can throw InvalidOtpLength error', function () {
    Otp::generate(-1);
})->throws(InvalidOtpLength::class, 'Invalid OTP length provided: -1');

it('can throw InvalidSetException error', function () {
    config()->set('otp.set', 'dummy');
    Otp::generate();
})->throws(InvalidSetException::class, 'Declared OTP set is empty.');

it('can throw InvalidIdentifierException error', function () {
    config()->set('otp.identifier', '');
    Otp::generateFor('email@example.com');
})->throws(InvalidIdentifierException::class, "OTP's `identifier` is not yet set.");

it('can generate unencrypted OTP without hash', function () {
    config()->set('otp.encrypt', false);
    Otp::generate()->hash;
})->throws(Exception::class, 'Undefined property');

it('can throw InvalidOtpHashAlgorithm error', function () {
    config()->set('otp.encrypt', true);
    config()->set('otp.alg', 'dummy');
    Otp::generate();
})->throws(InvalidOtpHashAlgorithm::class, 'Invalid hashing algorithim: dummy');
