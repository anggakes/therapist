<?php

namespace App\Http\Controllers\Order;

use App\Jobs\FindTherapist;
use App\Models\OfferingOrder;
use App\Models\OrderConnector;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class OrderController extends Controller
{
    //

    private $customerEndpoint;


    public function __construct()
    {
        $this->customerEndpoint = config('app.service_customer');
    }

    public function confirmation(){

        $user = request()->user();
        $invoiceNumber = request('invoice_number');

        // verification order
        $client = new Client(); //GuzzleHttp\Client
        /** @var \GuzzleHttp\Psr7\Response  $orderRaw */
        $orderRaw = $client->post($this->customerEndpoint."/api/web_service/order/get", [
            'form_params' => [
                'invoice_number' => $invoiceNumber
            ]
        ]);

        $order = \GuzzleHttp\json_decode($orderRaw->getBody()->getContents(), true);


        if($order['status']['id'] != 'FIND_THERAPIST' or $orderRaw->getStatusCode() != 200){
            throw new BadRequestHttpException('Invoice Number Salah');
        }

        if($order['therapist'] != null){
            throw new BadRequestHttpException('Terapis sudah ditemukan');
        }

        // check is eligible

        // ok save
        OrderConnector::create([
            'user_id' => $user->id,
            'invoice_number' => $invoiceNumber
        ]);


        // send notif to customer
        /** @var \GuzzleHttp\Psr7\Response  $setTherapist */
        $setTherapist = $client->post($this->customerEndpoint."/api/web_service/order/therapist", [
            'form_params' => [
                'invoice_number' => $invoiceNumber,
                'name' => $user->name,
                'handphone' => $user->handphone,
                'id' => $user->id,
                'email' => $user->email
            ]
        ]);

        if($setTherapist->getStatusCode() != 200){
            throw new BadRequestHttpException('Gagal set terapis pada server customer');
        }

        OfferingOrder::where('invoice_number','=', $invoiceNumber)->update(['accepted' => true]);

        //set order
        $order['therapist'] = [
            'invoice_number' => $invoiceNumber,
            'name' => $user->name,
            'handphone' => $user->handphone,
            'id' => $user->id,
            'email' => $user->email
        ];

        return response()->json($order);
    }



    public function cancel(){

    }
}
