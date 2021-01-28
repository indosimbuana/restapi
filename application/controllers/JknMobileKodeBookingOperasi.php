<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class JknMobileKodeBookingOperasi extends RestController
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
        $dt['nopeserta'] = isset($data['nopeserta']) && $data['nopeserta'] ? $data['nopeserta'] : '';

        if ($dt['nopeserta'] == NULL || $dt['nopeserta'] == '') {
            $this->response([
                'metadata' => [
                    'code' => 203,
                    'message' => 'Invalid nomor peserta'
                ]
            ], 203);
        } else {
            if (strlen($dt['nopeserta']) != 13) {
                $this->response([
                    'metadata' => [
                        'code' => 203,
                        'message' => 'Invalid nomor peserta'
                    ]
                ], 203);
            } else {
                $dbooking = array();
                $b = $this->mmobilejkn->getJadwalOperasi($dt['nopeserta']);
                if ($b) {
                    $n = 0;
                    foreach ($b as $j) {
                        $dbooking[$n]['kodebooking'] = $j['BookingID'];
                        $dbooking[$n]['tanggaloperasi'] = $j['TglOperasi'];
                        $dbooking[$n]['jenistindakan'] = $j['NAMAPMR'];
                        $dbooking[$n]['kodepoli'] = $j['KodeBPJS'];
                        $dbooking[$n]['namapoli'] = $j['NamaBagian'];
                        $dbooking[$n]['terlaksana'] = (int)$j['Terlaksana'];
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
