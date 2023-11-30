<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property-read int|null $id
 * @property Carbon|null $from
 * @property Carbon|null $to
 * @property string|null $reg_plate
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 */
class Booking extends Model
{
    use HasApiTokens;
    use HasFactory;

    protected $fillable = [
        'from',
        'to',
        'reg_plate'
    ];

    protected $casts = [
        'from' => 'datetime:Y-m-d',
        'to' => 'datetime:Y-m-d',
    ];
}
