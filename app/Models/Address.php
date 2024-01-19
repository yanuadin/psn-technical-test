<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $customer_id
 * @property string $address
 * @property string $district
 * @property string $city
 * @property string $province
 * @property int $postal_code
 */

class Address extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'address', 'district', 'city', 'province', 'postal_code'];
    protected $hidden = ['customer_id'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
