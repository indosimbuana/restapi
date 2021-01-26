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
        $dt['tanggalawal'] = $data['tanggalawal'];
        $dt['tanggalakhir'] = $data['tanggalakhir'];

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
