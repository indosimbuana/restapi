<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Antrian extends RestController
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
        $lokasi = $this->get('lokasi');
        $tgl = $this->get('tgl');

        $this->load->model('mantrian');
        if ($lokasi === null) {
            $this->response([
                'status' => false,
                'message' => 'Lokasi masih kosong'
            ], 203);
        } else {
            if ($lokasi == 'pendaftaran') {
                $an = $this->mantrian->pendaftaran($tgl);
                if ($an) {
                    $data = array();
                    $n = 0;
                    foreach ($an as $a) {
                        if (substr($a['noantri'], 0, 1) == 'A') {
                            $waktu = 'Pagi';
                            $judul = 'Obsgyn';
                        } else if (substr($a['noantri'], 0, 1) == 'B') {
                            $waktu = 'Pagi';
                            $judul = 'Non Obsgyn';
                        } else if (substr($a['noantri'], 0, 1) == 'C') {
                            $waktu = 'Sore';
                            $judul = 'Obsgyn';
                        } else if (substr($a['noantri'], 0, 1) == 'D') {
                            $waktu = 'Sore';
                            $judul = 'Non Obsgyn';
                        } else {
                            $waktu = '-';
                            $judul = '-';
                        }
                        $data[$n]['waktu'] = $waktu;
                        $data[$n]['judul'] = $judul;
                        $data[$n]['noantri'] = $a['noantri'];
                        $data[$n]['tanggal'] = date_format(date_create($a['tanggal']), 'd-m-Y');
                        // $data[$n]['panggil'] = $a['panggil'];
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
            } else {
                $an = $this->mantrian->poli($tgl);
                if ($an) {
                    $data = array();
                    $n = 0;
                    foreach ($an as $a) {
                        $data[$n]['noantri'] = $a['noantri'];
                        $data[$n]['klinik'] = $a['klinik'];
                        $data[$n]['dokter'] = $a['dokter'];
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
    }
}
