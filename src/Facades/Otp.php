<?php

namespace Morcen\LaravelOtpGenerator\Facades;

use Illuminate\Support\Facades\Facade;
use Morcen\LaravelOtpGenerator\LaravelOtpGenerator;

/**
 * @see \Morcen\LaravelOtpGenerator\LaravelOtpGenerator
 *
 * @method static LaravelOtpGenerator generate(?int $length = null)
 * @method static LaravelOtpGenerator generateFor(?int $length = null)
 * @method static LaravelOtpGenerator validateFor(?int $length = null)
 */
class Otp extends Facade
{
    protected static function getFacadeAccessor()
    {
        return LaravelOtpGenerator::class;
    }
}
