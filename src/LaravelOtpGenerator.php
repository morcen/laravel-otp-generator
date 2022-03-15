<?php

namespace Morcen\LaravelOtpGenerator;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Hash;
use Morcen\LaravelOtpGenerator\Exceptions\InvalidIdentifierException;
use Morcen\LaravelOtpGenerator\Exceptions\InvalidOtpHashAlgorithm;
use Morcen\LaravelOtpGenerator\Exceptions\InvalidOtpLength;
use Morcen\LaravelOtpGenerator\Exceptions\InvalidSetException;
use Morcen\LaravelOtpGenerator\Models\Otp;

class LaravelOtpGenerator extends Facade
{
    private const CODE_KEY = 'code';
    private const HASH_KEY = 'hash';
    private const EXPIRY_KEY = 'expiration';

    public const NUMBER_SET = 'numbers';
    public const LOWER_CASE_SET = 'lower';
    public const UPPER_CASE_SET = 'uppercase';
    public const OTHERS_SET = 'others';

    /**
     * @param  string  $identifierValue
     * @param  int|null  $length
     * @return object
     * @throws InvalidIdentifierException
     * @throws InvalidOtpLength
     * @throws InvalidSetException
     */
    public function generateFor(string $identifierValue, ?int $length = null): object
    {
        $identifier = $this->getIdentifier();
        Otp::where($identifier, '=', $identifierValue)->delete();

        $otp = $this->generate($length);
        $otpProperty = $this->isEncrypted() ? self::HASH_KEY : self::CODE_KEY;
        $expiration = now()->addMinutes(config('otp.lifetime'))->timestamp;

        Otp::create([
            $identifier => $identifierValue,
            'code' => $otp->$otpProperty,
            'created_at' => now(),
            'expiration' => $expiration,
        ]);

        $response = [
            $identifier => $identifierValue,
            self::CODE_KEY => $otp->code,
            self::EXPIRY_KEY => $expiration,
        ];
        if ($this->isEncrypted()) {
            $response[self::HASH_KEY] = $otp->hash;
        }

        return (object)$response;
    }

    /**
     * @param  int|null  $length
     * @return object
     * @throws InvalidOtpLength
     * @throws InvalidSetException|InvalidOtpHashAlgorithm
     */
    public function generate(?int $length = null): object
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

        $response = [self::CODE_KEY => $code];

        if ($this->isEncrypted()) {
            $response[self::HASH_KEY] = $this->hash($code);
        }

        return (object)$response;
    }

    /**
     * @param  string  $identifierValue
     * @param  string  $code
     * @return bool
     * @throws InvalidIdentifierException
     * @throws InvalidOtpHashAlgorithm
     */
    public function validateFor(string $identifierValue, string $code): bool
    {
        if ($this->isEncrypted() ) {
            $code = $this->hash($code);
        }

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

        if (empty($identifier)) {
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

    /**
     * @param  string  $code
     * @return string
     * @throws InvalidOtpHashAlgorithm
     */
    private function hash(string $code): string
    {
        $alg = config('otp.alg');

        if (!in_array($alg, ['md5', 'sha1'])) {
            throw new InvalidOtpHashAlgorithm('Invalid hashing algorithim: ' . $alg);
        }

        return hash($alg, $code);
    }

    /**
     * @return bool
     */
    private function isEncrypted(): bool
    {
        return  config('otp.encrypt', false);
    }
}
