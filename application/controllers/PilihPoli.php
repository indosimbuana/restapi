<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class PilihPoli extends RestController
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
        $jns = $this->get('jns');
        $tgl = $this->get('tgl');
        $t = new DateTime($tgl);

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

        $this->load->model('mpilihpoli');
        if ($jns === null || $tgl === null) {
            $this->response([
                'status' => false,
                'message' => 'Jenis Waktu dan Tanggal harus diisi'
            ], 404);
        } else {
            $p = $this->mpilihpoli->getJadwalPoliBuka($jns, $hari);

            if ($p) {
                $n = 0;
                foreach ($p as $dt) {
                    // $klanak = array(date('yy-m-d', strtotime('second sat of august this year')), date('yy-m-d', strtotime('fourth sat of august this year')));
                    // if ($dt['KodeKlinik'] != "6104" && !in_array($tgl, $klanak)) {
                    $data[$n]['kdklinik'] = $dt['KodeKlinik'];
                    $data[$n]['nmklinik'] = $dt['NamaBagian'];
                    $data[$n]['kddokter'] = $dt['KodeDokter'];
                    $data[$n]['nmdokter'] = $dt['NamaDokter'];
                    $data[$n]['buka'] = date_format(date_create($dt[$hari]), "H:i");
                    $data[$n]['tutup'] = date_format(date_create($dt[$hari . 'Tutup']), "H:i");
                    $data[$n]['ket'] = $dt['Keterangan'];
                    // }
                    $n++;
                }
                $this->response([
                    'status' => true,
                    'message' => 'Data found',
                    // 'hari' => $hari,
                    'data' => $data
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No data were found'
                ], 404);
            }
        }
    }
}
