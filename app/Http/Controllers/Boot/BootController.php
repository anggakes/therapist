<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/4/17
 * Time: 9:59 PM
 */

namespace App\Http\Controllers\Boot;


use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;
use JWTAuth;

class BootController extends Controller
{

    public function index(){
        $user = false;
        try {
            $user = JWTAuth::parseToken()->authenticate();
        }catch (\Exception $e){
            $user = false;
        }

        /** @var Banner $banner */
        $banners = Banner::getActiveBanners();

        $data = [
            "banners" => $banners,
            "is_update" => false,
            "text" => [
                'thank_you' => "Terima Kasih Order anda sedang d proses"
            ]
        ];

        return $data;
    }
}