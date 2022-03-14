<?php

namespace Morcen\LaravelOtpGenerator\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Otp
 *
 * @property int $id
 * @property string $code
 * @property integer $expiration
 */
class Otp extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $timestamps = false;
}
