<?php

namespace Morcen\LaravelOtpGenerator;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Morcen\LaravelOtpGenerator\Commands\LaravelOtpGeneratorCommand;

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
