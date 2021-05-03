<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class PoliAktif extends RestController
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

        $an = $this->mantrian->poli($tgl);
        if ($an) {
            $data = array();
            $n = 0;
            foreach ($an as $a) {
                $data[$n]['noantri'] = $a['noantri'];
                $data[$n]['klinik'] = $a['klinik'];
                $data[$n]['dokter'] = $a['dokter'];
                $data[$n]['nopasien'] = $a['nopasien'];
                $data[$n]['nama'] = $a['nama'];
                $data[$n]['alamat'] = $a['alamat'];
                $data[$n]['tanggal'] = date_format(date_create($a['tanggal']), 'd-m-Y');
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
