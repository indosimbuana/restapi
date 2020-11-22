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
        $poli = $this->get('poli');
        $tgl = $this->get('tgl');

        $np = str_pad($nopas + 1, 8, "0", STR_PAD_LEFT);

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
                $this->response([
                    'status' => true,
                    'message' => 'Data found',
                    'sekarang' => $i,
                    'blmmasuk' => $b->Jumlah,
                    'sdhmasuk' => $m->Jumlah,
                    'dilewati' => $l->Jumlah
                ], 200);
                // } else {
                //     $this->response([
                //         'status' => false,
                //         'message' => 'No info were found'
                //     ], 404);
                // }
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'No info were found'
            ], 404);
        }
    }
}
