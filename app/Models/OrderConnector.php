<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderConnector extends Model
{
    //
    protected $fillable = [
        'invoice_number', 'user_id'
    ];
}
