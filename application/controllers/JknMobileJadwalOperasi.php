<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class JknMobileJadwalOperasi extends RestController
{

    function __construct()
    {
        parent::__construct();
        $cek = $this->jkntoken->cek();
        if ($cek['status'] == false) {
            $this->response([
                'metadata' => [
                    'code' => 401,
                    'message' => $cek['message']
                ]
            ], 401);
        }
    }

    public function index_post()
    {
        $d = file_get_contents('php://input');
        $data = json_decode($d, true);

        $this->load->model('mmobilejkn');

        $dt = array();
        $dt['tanggalawal'] = isset($data['tanggalawal']) && $data['tanggalawal'] ? $data['tanggalawal'] : '';
        $dt['tanggalakhir'] = isset($data['tanggalakhir']) && $data['tanggalakhir'] ? $data['tanggalakhir'] : '';

        $this->load->library('validatedate');

        if ($dt['tanggalawal'] == NULL || $dt['tanggalawal'] == '' || $this->validatedate->cekDate($dt['tanggalawal']) === false) {
            $this->response([
                'metadata' => [
                    'code' => 203,
                    'message' => 'Invalid tanggal awal'
                ]
            ], 203);
        } else {
            if ($dt['tanggalakhir'] == NULL || $dt['tanggalakhir'] == '' || $this->validatedate->cekDate($dt['tanggalakhir']) === false) {
                $this->response([
                    'metadata' => [
                        'code' => 203,
                        'message' => 'Invalid tanggal akhir'
                    ]
                ], 203);
            } else {
                if ($dt['tanggalawal'] > $dt['tanggalakhir']) {
                    $this->response([
                        'metadata' => [
                            'code' => 203,
                            'message' => 'Invalid tanggal awal lebih besar dari tanggal akhir'
                        ]
                    ], 203);
                } else {
                    $dbooking = array();
                    $b = $this->mmobilejkn->getListJadwalOperasi($dt['tanggalawal'], $dt['tanggalakhir']);
                    if ($b) {
                        $n = 0;
                        foreach ($b as $j) {
                            $dbooking[$n]['kodebooking'] = $j['BookingID'];
                            $dbooking[$n]['tanggaloperasi'] = $j['TglOperasi'];
                            $dbooking[$n]['jenistindakan'] = $j['NAMAPMR'];
                            $dbooking[$n]['kodepoli'] = $j['KodeBPJS'];
                            $dbooking[$n]['namapoli'] = $j['NamaBagian'];
                            $dbooking[$n]['terlaksana'] = $j['Batal'] == 1 ? 2 : (int)$j['Terlaksana'];
                            $dbooking[$n]['nopeserta'] = $j['NoKartuBPJS'];
                            $dbooking[$n]['lastupdate'] = strtotime(date("Y-m-d H:i:s")) * 1000;
                            $n++;
                        }

                        $this->response([
                            'metadata' => [
                                'code' => 200,
                                'message' => 'OK'
                            ],
                            'response' => [
                                'list' => $dbooking
                            ]
                        ], 200);
                    } else {
                        $this->response([
                            'metadata' => [
                                'code' => 203,
                                'message' => 'Tidak ada jadwal operasi'
                            ]
                        ], 203);
                    }
                }
            }
        }
    }
}
