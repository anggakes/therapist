<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 3/4/17
 * Time: 11:36 AM
 */

namespace App\Utils;


class ResponseModel
{
    public $statusCode = 200;
    public $status = "success";
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

}