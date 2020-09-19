<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Mstprovinsi extends RestController
{

    function __construct()
    {
        parent::__construct();
        $cek = $this->token->cek();
        if ($cek['status'] == false) {
            $this->response([
                'status' => $cek['status'],
                'message' => $cek['message']
            ], $cek['code']);
        }
    }

    public function index_get()
    {
        $id = $this->get('id');

        $this->load->model('mprovinsi');
        if ($id === null) {
            $p = $this->mprovinsi->getProvinsi();
            if ($p) {
                $n = 0;
                foreach ($p as $dt) {
                    $data[$n]['kdprov'] = $dt['KODEPROV'];
                    $data[$n]['nmprov'] = $dt['PROVINCE'];
                    $n++;
                }
                $this->response([
                    'status' => true,
                    'message' => 'Data found',
                    'data' => $data
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No provinsi were found'
                ], 404);
            }
        } else {
            $dp = $this->mprovinsi->getProvinsiById($id);
            if ($dp) {
                $data['kdprov'] = $dp->KODEPROV;
                $data['nmprov'] = $dp->PROVINCE;
                $this->response([
                    'status' => true,
                    'message' => 'Data found',
                    'data' => $data
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No provinsi were found'
                ], 404);
            }
        }
    }
}
