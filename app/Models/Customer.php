<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $title
 * @property string $name
 * @property string $gender
 * @property string $phone_number
 * @property string $image
 * @property string $email
 */

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'name', 'gender', 'phone_number', 'image', 'email'];

    public const GENDER_MALE = 'M';
    public const GENDER_FEMALE = 'F';

    public static function getGenders(): array
    {
        return [
            ['label' => 'Male', 'value' => self::GENDER_MALE],
            ['label' => 'Female', 'value' => self::GENDER_FEMALE],
        ];
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class, 'customer_id');
    }
}
