<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Antrianpoli extends RestController
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
        $tgl = $this->get('tgl');

        $this->load->model('mantrian');
        if ($tgl === null) {
            $this->response([
                'status' => false,
                'message' => 'Tanggal masih kosong'
            ], 203);
        } else {

            $an = $this->mantrian->semuapoli($tgl);
            if ($an) {
                $data = array();
                $n = 0;
                foreach ($an as $a) {
                    $data[$n]['klinik'] = ucwords($a['klinik']);
                    $data[$n]['total'] = $a['total'];
                    $data[$n]['jumlahmasuk'] = $a['jumlahmasuk'];
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
                    'message' => 'Data not found'
                ], 404);
            }
        }
    }
}
