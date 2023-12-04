<?php

namespace App\Models;

use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use InvalidArgumentException;
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

    public const REG_PLATE_REGEX = '[A-Z]{2}[0-9]{2} [A-Z]{3}';

    /**
     * Check that there is at least one spot free on every day between from and to
     *
     * @throws InvalidArgumentException
     */
    public static function checkDatesAreFree(Carbon $from, Carbon $to): bool
    {
        // startOfDay is used as time is inconsequential since bookings are for days not hours
        throw_if(
            $from->startOfDay()
                ->greaterThan(
                    $to->startOfDay()
                ),
            new InvalidArgumentException('$from cannot be greater than $to')
        );

        foreach(CarbonPeriod::between($from, $to)->days() as $_day) {
            if(
                Booking::query()
                    ->where('from', '<=', $_day)
                    ->where('to', '>=', $_day)
                    ->count()
                    >=
                    config('app.settings.parking_spaces')
            ) {
                return false;
            }
        }

        return true;
    }
}
