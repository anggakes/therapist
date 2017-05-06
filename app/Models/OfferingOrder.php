<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferingOrder extends Model
{
    //

    protected $fillable = [
        'invoice_number',
        'therapist_id',
        'customer_id',
        'accepted'
    ];
}
