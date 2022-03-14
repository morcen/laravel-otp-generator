<?php

namespace Morcen\LaravelOtpGenerator\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Morcen\LaravelOtpGenerator\LaravelOtpGenerator
 */
class Otp extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Morcen\LaravelOtpGenerator\LaravelOtpGenerator::class;
    }
}
