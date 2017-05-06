<?php

namespace App\Jobs;

use App\Models\CurrentLocation;
use App\Models\OfferingOrder;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FindTherapist implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $invoiceNumber;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;
        //
    }

    /**
     * Execute the job.
     *
     * @throws \Exception
     */
    public function handle()
    {
        $customerEndpoint = config('app.service_customer');
        // get order from customer
        $client = new Client(); //GuzzleHttp\Client
        /** @var \GuzzleHttp\Psr7\Response  $orderRaw */
        $orderRaw = $client->post($customerEndpoint."/api/web_service/order/get", [
            'form_params' => [
                'invoice_number' => $this->invoiceNumber
            ]
        ]);

        $order = \GuzzleHttp\json_decode($orderRaw->getBody()->getContents(), true);


        if($order['status']['id'] != 'FIND_THERAPIST' or $orderRaw->getStatusCode() != 200){
            throw new \Exception('Invoice Number Salah');
        }

        // get therapistId
        $hasOfferedList = OfferingOrder::where('invoice_number','=', $this->invoiceNumber)->get();
        $hasOfferedTherapistId = [];
        foreach ($hasOfferedList as $ho){
            $hasOfferedTherapistId[] = $ho->therapist_id;
        }

        $lat = $order['location']['lat'];
        $lng = $order['location']['lng'];
        $nearby = new CurrentLocation();
        $listTherapist = $nearby->whereRaw([
            "loc" =>[
                '$near' => [
                    (float) $lng, (float) $lat
                ],
            ],
            "user_id" =>['$nin' => $hasOfferedTherapistId]
        ])->limit(1)->skip(0)->get();

        if(count($listTherapist)<=0) throw new \Exception('tidak ada therapist');

        // now only get the first if not Offered to her
        $therapistId = $listTherapist[0]->user_id;


        // if has 3 times assign this invoice to therapistId


        // send push notif
        Storage::disk('local')->put('file_job.txt', $therapistId);

        // save offering
        OfferingOrder::create([
            'invoice_number'    => $this->invoiceNumber,
            'therapist_id'      => $therapistId,
            'customer_id'           => $order['customer']['id']
        ]);

        // queue next find therapist
        $job = (new FindTherapist($this->invoiceNumber))->delay(Carbon::now()->addMinutes(5));

        dispatch($job);

    }
}
