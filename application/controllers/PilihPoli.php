<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class PilihPoli extends RestController
{

    function __construct()
    {
        parent::__construct();
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
            $p = $this->mpilihpoli->getJadwalPoli($jns);

            if ($p) {
                $n = 0;
                foreach ($p as $dt) {
                    $dr = $this->mpilihpoli->getDokterLibur($tgl, $dt['KodeDokter']);
                    $kl = $this->mpilihpoli->getKlinikLibur($tgl, $dt['KodeKlinik']);
                    $data[$n]['kdklinik'] = $dt['KodeKlinik'];
                    $data[$n]['nmklinik'] = $dt['NamaBagian'];
                    $data[$n]['kddokter'] = $dt['KodeDokter'];
                    $data[$n]['nmdokter'] = $dt['Nama'];
                    if ($kl) {
                        $bk = "Libur - " . $kl->Keterangan;
                    } else if ($dr) {
                        $bk = "Libur - " . $dr->Keterangan;
                    } else {
                        if ($dt[$hari] === NULL) {
                            $bk = "Libur";
                        } else {
                            $bk = date_format(date_create($dt[$hari]), "H:i");
                        }
                    }
                    $data[$n]['buka'] = $bk;
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
                    'message' => 'No users were found'
                ], 404);
            }
        }
    }
}
