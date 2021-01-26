<?php
defined('BASEPATH') or exit('No direct script access allowed');

use \Firebase\JWT\JWT;

use chriskacerguis\RestServer\RestController;

class JknMobileLogin extends RestController
{

    function __construct()
    {
        parent::__construct();
    }

    public function index_post()
    {
        $this->load->library('enkripdekrip');

        $d = file_get_contents('php://input');
        $data = json_decode($d, true);

        $dt = array();
        $dt['username'] = isset($data['username']) && $data['username'] ? $data['username'] : '';
        $dt['password'] = isset($data['password']) && $data['password'] ? $this->enkripdekrip->proses($data['password']) : '';

        $this->load->model('mmobilejkn');
        $cek = $this->mmobilejkn->cekAkun($dt);

        if ($dt['username'] == '' && $dt['password'] == '') {
            $this->response([
                'metadata' => [
                    'code' => 203,
                    'message' => 'Invalid Login'
                ]
            ], 203);
        } else {
            if ($cek) {
                date_default_timezone_set("Asia/Jakarta");
                $data['secretkey'] = "yIMnDdYHyHfdzEuo2chgGJwkuA5ZtU8Rg5HcSf83EmtLXuF7XuUNupWp8zmkhKIx";
                $payload = array(
                    "timestamp" => time()
                );
                $data['timestamp'] = $payload['timestamp'];

                $token = JWT::encode($data, $data['secretkey']);

                $this->response([
                    'metadata' => [
                        'code' => 200,
                        'message' => 'OK'
                    ],
                    'response' => [
                        'token' => $token
                    ]
                ], 200);
            } else {
                $this->response([
                    'metadata' => [
                        'code' => 203,
                        'message' => 'Invalid Login'
                    ]
                ], 203);
            }
        }
    }
}
