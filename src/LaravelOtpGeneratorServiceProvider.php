<?php

namespace Morcen\LaravelOtpGenerator;

use Morcen\LaravelOtpGenerator\Commands\LaravelOtpGeneratorCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelOtpGeneratorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-otp-generator')
            ->hasConfigFile('otp')
            ->hasMigration('create_otp_generator_table')
            ->hasCommand(LaravelOtpGeneratorCommand::class);
    }
}
