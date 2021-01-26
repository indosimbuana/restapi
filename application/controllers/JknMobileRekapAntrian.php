<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class JknMobileRekapAntrian extends RestController
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
        $this->load->model('mpoli');
        $this->load->model('mantrian');

        $dt = array();
        $dt['tanggalperiksa'] = $data['tanggalperiksa'];
        $dt['kodepoli'] = $data['kodepoli'];
        $dt['polieksekutif'] = $data['polieksekutif'];

        $t = new DateTime($dt['tanggalperiksa']);

        switch ($t->format('D')) {
            case "Sun":
                $hari = "Minggu";
                break;
            case "Mon":
                $hari = "Senin";
                break;
            case "Tue":
                $hari = "Selasa";
                break;
            case "Wed":
                $hari = "Rabu";
                break;
            case "Thu":
                $hari = "Kamis";
                break;
            case "Fri":
                $hari = "Jumat";
                break;
            case "Sat":
                $hari = "Sabtu";
                break;
            default:
                $hari = "";
        }

        if ($dt['kodepoli'] == NULL || $dt['kodepoli'] == '') {
            $this->response([
                'code' => 203,
                'message' => 'Silahkan pilih poli dahulu'
            ], 203);
        } else {
            $getkodepoli = $this->mmobilejkn->getKodePoli($dt['kodepoli']);

            if ($getkodepoli) {

                if ($getkodepoli->RuangId == '6101') {
                    $cekjampolipagi = $this->mmobilejkn->getJamPoliKebidanan('P', $hari);
                } else {
                    $cekjampolipagi = $this->mmobilejkn->getJamPoli($getkodepoli->RuangId, 'P');
                }

                if ($cekjampolipagi) {
                    if ($cekjampolipagi->$hari != NULL || $cekjampolipagi->$hari !=  "00:00:00.00000") {

                        $poliantrian = $this->mpoli->getPoliById($cekjampolipagi->KodeKlinik);

                        if ($poliantrian) {
                            $i = $this->mantrian->poli($poliantrian->KodeAntrian, $dt['tanggalperiksa']);
                            $b = $this->mantrian->totalBelumMasuk($poliantrian->KodeAntrian, $dt['tanggalperiksa']);
                            $m = $this->mantrian->totalSudahMasuk($poliantrian->KodeAntrian, $dt['tanggalperiksa']);
                            $l = $this->mantrian->totalDilewati($poliantrian->KodeAntrian, $dt['tanggalperiksa']);

                            $this->response([
                                'metadata' => [
                                    'code' => 200,
                                    'message' => 'OK'
                                ],
                                'response' => [
                                    'namapoli' => $poliantrian->KodeAntrian,
                                    'totalantrean' => $b ? $b->Jumlah + $m->Jumlah + $l->Jumlah : '',
                                    'jumlahterlayani' => $m ? (int)$m->Jumlah : '',
                                    'lastupdate' => strtotime(date("Y-m-d H:i:s")) * 1000
                                ]
                            ], 200);
                        } else {
                            $this->response([
                                'code' => 203,
                                'message' => 'Informasi poli tidak tersedia'
                            ], 203);
                        }
                    } else {
                        if ($getkodepoli->RuangId == '6101') {
                            $cekjampolisore =  $this->mmobilejkn->getJamPoliKebidanan('S', $hari);
                        } else {
                            $cekjampolisore = $this->mmobilejkn->getJamPoli($getkodepoli->RuangId, 'S');
                        }

                        if ($cekjampolisore) {
                            if ($cekjampolisore->$hari != NULL || $cekjampolisore->$hari !=  "00:00:00.00000") {

                                $poliantrian = $this->mpoli->getPoliById($cekjampolisore->KodeKlinik);

                                if ($poliantrian) {
                                    $i = $this->mantrian->poli($poliantrian->KodeAntrian, $dt['tanggalperiksa']);
                                    $b = $this->mantrian->totalBelumMasuk($poliantrian->KodeAntrian, $dt['tanggalperiksa']);
                                    $m = $this->mantrian->totalSudahMasuk($poliantrian->KodeAntrian, $dt['tanggalperiksa']);
                                    $l = $this->mantrian->totalDilewati($poliantrian->KodeAntrian, $dt['tanggalperiksa']);

                                    $this->response([
                                        'metadata' => [
                                            'code' => 200,
                                            'message' => 'OK'
                                        ],
                                        'response' => [
                                            'namapoli' => $poliantrian->KodeAntrian,
                                            'totalantrean' => $b ? $b->Jumlah + $m->Jumlah + $l->Jumlah : '',
                                            'jumlahterlayani' => $m ? (int)$m->Jumlah : '',
                                            'lastupdate' => strtotime(date("Y-m-d H:i:s")) * 1000
                                        ]
                                    ], 200);
                                } else {
                                    $this->response([
                                        'code' => 203,
                                        'message' => 'Informasi poli tidak tersedia'
                                    ], 203);
                                }
                            } else {
                                $this->response([
                                    'code' => 203,
                                    'message' => 'Informasi poli tidak tersedia'
                                ], 203);
                            }
                        } else {
                            $this->response([
                                'code' => 203,
                                'message' => 'Informasi poli tidak tersedia'
                            ], 203);
                        }
                    }
                } else {
                    $this->response([
                        'code' => 203,
                        'message' => 'Informasi poli tidak tersedia'
                    ], 203);
                }
            }
        }
    }
}
