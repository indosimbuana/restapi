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
        $poli = str_replace('%20', ' ', $this->get('poli'));
        $tgl = $this->get('tgl');

        $this->load->model('mantrian');
        if ($lokasi === "pendaftaran") {
            $i = $this->mantrian->pendaftaran();
            if ($i) {
                $this->response([
                    'status' => true,
                    'message' => 'Data found',
                    'data' => $i
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No info were found'
                ], 404);
            }
        } else if ($lokasi === "poli") {
            if ($poli === NULL and $tgl === NULL) {
                $p = $this->mantrian->daftarpoli();
                if ($p) {
                    $n = 0;
                    foreach ($p as $dt) {
                        if (trim($dt['Klinik']) != '') {
                            $data[$n]['poli'] = $dt['Klinik'];
                            $n++;
                        }
                    }
                    $this->response([
                        'status' => true,
                        'message' => 'Data found',
                        'data' => $data
                    ], 200);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'No bagian were found'
                    ], 404);
                }
            } else {
                if ($poli === NULL) {
                    $this->response([
                        'status' => false,
                        'message' => 'No info were found'
                    ], 404);
                } else {
                    $i = $this->mantrian->poli($poli, $tgl);
                    $b = $this->mantrian->totalBelumMasuk($poli, $tgl);
                    $m = $this->mantrian->totalSudahMasuk($poli, $tgl);
                    $l = $this->mantrian->totalDilewati($poli, $tgl);
                    // if ($i) {
                    $data = array(
                        'no' => $i ? $i->NO : '',
                        'poli' => $i ? $i->Klinik : '',
                        'dokter' => $i ? $i->DOKTER : '',
                        'nopasien' => $i ? str_pad($i->No_Pasien + 1, 8, "0", STR_PAD_LEFT) : '',
                        'namapasien' => $i ? $i->Nama : ''
                    );
                    $this->response([
                        'status' => true,
                        'message' => 'Data found',
                        'sekarang' => $data,
                        'blmmasuk' => $b ? $b->Jumlah : '',
                        'sdhmasuk' => $m ? $m->Jumlah : '',
                        'dilewati' => $l ? $l->Jumlah : ''
                    ], 200);
                    // } else {
                    //     $this->response([
                    //         'status' => false,
                    //         'message' => 'No info were found'
                    //     ], 404);
                    // }
                }
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'No info were found'
            ], 404);
        }
    }
}
