<?php

namespace App\Http\Controllers\Order;

use App\Jobs\FindTherapist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderWebServiceController extends Controller
{
    //
    public function findTherapist(){
        $invoiceNumber = request('invoice_number');

        $job = (new FindTherapist($invoiceNumber));

        dispatch($job);
    }

}
