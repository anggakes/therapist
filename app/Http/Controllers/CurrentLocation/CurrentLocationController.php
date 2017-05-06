<?php

namespace App\Http\Controllers\CurrentLocation;

use App\Jobs\FindTherapist;
use App\Models\CurrentLocation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CurrentLocationController extends Controller
{
    //
    public function update(){
        $lat = (float) request('lat');
        $lng = (float) request('lng');
        $userId = request()->user()->id;
        $loc = [
            (float) $lng, (float) $lat,
        ];

        // delete the oldest
        CurrentLocation::where('user_id','=', $userId)->delete();
        $currentLocation  = CurrentLocation::create(['user_id'=> $userId, 'loc' => $loc]);

        return $currentLocation;

    }
}
