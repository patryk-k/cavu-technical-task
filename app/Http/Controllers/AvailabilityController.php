<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetAvailabilityRequest;
use App\Models\Booking;
use Carbon\CarbonPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use stdClass;

class AvailabilityController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(GetAvailabilityRequest $request): JsonResponse
    {
        /** @var int */
        $minFreeSpaces = $available = config('app.settings.parking_spaces');
        /** @var float */
        $defaultPrice = config('app.settings.prices.default');
        /** @var array<int, stdClass> */
        $specialPrices = config('app.settings.prices.special');

        foreach($specialPrices as $_specialPrice) {
            $_specialPrice->from = Carbon::createFromFormat('m-d', $_specialPrice->from);
            $_specialPrice->to = Carbon::createFromFormat('m-d', $_specialPrice->to);
        }

        $dailyData = new Collection();
        $totalPrice = 0;

        foreach(CarbonPeriod::between($request->from, $request->to)->days() as $_day) {
            $freeSpaces = $available -
                Booking::query()
                ->where('from', '<=', $_day)
                ->where('to', '>=', $_day)
                ->count();

            $price = $defaultPrice;

            foreach($specialPrices as $_specialPrice) {
                // this way we can compare special prices dates which are automatically set to current year
                $dayCarbon = clone $_day;
                $dayCarbon->setYear(now()->format('Y'));

                if($dayCarbon->greaterThanOrEqualTo($_specialPrice->from) && $dayCarbon->lessThanOrEqualTo($_specialPrice->to)) {
                    $price = $_specialPrice->price;
                }
            }

            $dailyData->push((object) [
                'day' => $_day->format('Y-m-d'),
                'price' => $price,
                'freeSpaces' => $freeSpaces
            ]);

            if($freeSpaces < $minFreeSpaces) {
                $minFreeSpaces = $freeSpaces;
            }

            $totalPrice += $price;
        }

        return new JsonResponse((object) [
            'message' => 'There is parking available between ' . $request->from . ' and ' . $request->to . ' for £' . $totalPrice,
            'min_free_spaces' => $minFreeSpaces,
            'total_price' => $totalPrice,
            'days' => $dailyData
        ]);
    }
}
