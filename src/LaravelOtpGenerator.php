<?php

namespace Morcen\LaravelOtpGenerator;

use Illuminate\Support\Facades\Facade;
use Morcen\LaravelOtpGenerator\Exceptions\InvalidIdentifierException;
use Morcen\LaravelOtpGenerator\Exceptions\InvalidOtpLength;
use Morcen\LaravelOtpGenerator\Exceptions\InvalidSetException;
use Morcen\LaravelOtpGenerator\Models\Otp;

class LaravelOtpGenerator extends Facade
{
    public const NUMBER_SET = 'numbers';
    public const LOWER_CASE_SET = 'lower';
    public const UPPER_CASE_SET = 'uppercase';
    public const OTHERS_SET = 'others';

    /**
     * @param  string  $identifierValue
     * @param  int|null  $length
     * @return Otp
     * @throws InvalidIdentifierException
     * @throws InvalidOtpLength
     * @throws InvalidSetException
     */
    public function generateFor(string $identifierValue, ?int $length = null): Otp
    {
        $identifier = $this->getIdentifier();
        $otpRecord = Otp::where($identifier, '=', $identifierValue)->first();

        if ($otpRecord && $this->expired($otpRecord)) {
            $otpRecord->delete();
        }

        return Otp::create([
            $identifier => $identifierValue,
            'code' => $this->generate($length),
            'created_at' => now(),
            'expiration' => now()->addMinutes(config('otp.expiration'))->timestamp,
        ]);
    }

    /**
     * @param  int|null  $length
     * @return string
     * @throws InvalidSetException
     * @throws InvalidOtpLength
     */
    public function generate(?int $length = null): string
    {
        if (! is_null($length) && $length <= 0) {
            throw new InvalidOtpLength('Invalid OTP length provided: ' . $length);
        }

        $set = $this->getSet();

        if (! $length) {
            $length = config('otp.length');
        }

        $code = "";

        for ($count = 1; $count <= $length; $count++) {
            $code .= $set[mt_rand(0, strlen($set) - 1)];
        }

        return $code;
    }

    public function validateFor(string $identifierValue, string $code): bool
    {
        $identifier = $this->getIdentifier();
        $otpRecord = Otp::where($identifier, '=', $identifierValue)
            ->where('code', '=', $code)
            ->first();

        return $otpRecord && ! $this->expired($otpRecord);
    }

    /**
     * @return string
     * @throws InvalidIdentifierException
     */
    private function getIdentifier(): string
    {
        $identifier = config('otp.identifier');

        if (! $identifier) {
            throw new InvalidIdentifierException("OTP's `identifier` is not yet set.");
        }

        return $identifier;
    }

    /**
     * @param  Otp  $otp
     * @return bool
     */
    private function expired(Otp $otp): bool
    {
        return now()->timestamp > $otp->expiration;
    }

    /**
     * @return string
     * @throws InvalidSetException
     */
    private function getSet(): string
    {
        $set = config('otp.set');

        if (empty($set)) {
            throw new InvalidSetException('Declared OTP set is empty.');
        }

        if (is_string($set)) {
            if ($set === "all") {
                $arraySet = [
                    config('otp.' . self::NUMBER_SET),
                    config('otp.' . self::LOWER_CASE_SET),
                    config('otp.' . self::UPPER_CASE_SET),
                    config('otp.' . self::OTHERS_SET),
                ];

                return implode(null, $arraySet);
            }

            $chosenSet = config('otp.' . $set);
            if (! empty($chosenSet)) {
                return $chosenSet;
            }

            throw new InvalidSetException('Declared OTP set is empty.');
        }

        $combinedSet = [];
        foreach ($set as $item) {
            $currentSet = config('otp.' . $set);
            if (! empty($currentSet)) {
                $combinedSet = array_merge($combinedSet, $currentSet);
            }
        }

        if (! empty($combinedSet)) {
            return implode(null, $combinedSet);
        }

        throw new InvalidSetException('Declared OTP set is empty.');
    }
}
