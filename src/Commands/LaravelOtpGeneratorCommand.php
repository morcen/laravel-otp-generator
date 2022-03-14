<?php

namespace Morcen\LaravelOtpGenerator\Commands;

use Illuminate\Console\Command;

class LaravelOtpGeneratorCommand extends Command
{
    public $signature = 'laravel-otp-generator';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
