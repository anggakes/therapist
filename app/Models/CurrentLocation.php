<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 4/19/17
 * Time: 12:49 PM
 */

namespace App\Models;


use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class CurrentLocation extends Eloquent
{
    protected $collection = 'therapist_location';
    protected $connection = 'mongodb';

    protected $fillable = [
      'user_id', 'loc'
    ];

    public function getNearby($lat, $lng, $limit, $offset){
        return $this->whereRaw([
            "loc" =>[
                '$near' => [
                    (float) $lng, (float) $lat
                ]
            ]
        ])->limit($limit)->skip($offset)->get();
    }
}