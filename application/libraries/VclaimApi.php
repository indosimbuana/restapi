<?php

defined('BASEPATH') or exit('No direct script access allowed');

class VclaimApi
{

    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function aksesws()
    {
        $data = "11297";
        $secretKey = "8uX40B2DB6";

        // $data = "1932";
        // $secretKey = "rs12ms3";

        date_default_timezone_set('UTC');
        $date = new DateTime();
        $tStamp = $date->getTimestamp();
        $signature = hash_hmac('sha256', $data . "&" . $tStamp, $secretKey, true);

        $encodedSignature = base64_encode($signature);

        $akses = array();
        $akses['X-timestamp'] = $tStamp;
        $akses['X-signature'] = $encodedSignature;
        $akses['X-cons-id'] = $data;
        $akses['burl'] = "https://new-api.bpjs-kesehatan.go.id:8080/";
        $akses['service'] = "new-vclaim-rest/";
        // $akses['burl'] = "https://dvlp.bpjs-kesehatan.go.id/";
        // $akses['service'] = "VClaim-rest/";

        return $akses;
    }
}
