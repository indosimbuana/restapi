<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class RegBooking extends RestController
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

    public function index_post()
    {
        $d = file_get_contents('php://input');
        $data = json_decode($d, true);

        $date = date('yymd');

        $this->load->model('mregbooking');

        $dt = array();
        $dt['idanggotakeluarga'] = $data['idanggotakeluarga'];

        $dt['bagian'] = $data['bagian'];
        $getb = $this->mregbooking->getBagian($data['bagian']);
        if ($getb) {
            $dt['namabagian'] = $getb->NamaBagian;
        } else {
            $dt['namabagian'] = "";
        }

        $dt['penjamin'] = $data['penjamin'];
        $getp = $this->mregbooking->getPenjamin($data['penjamin']);
        if ($getp) {
            $dt['namapenjamin'] = $getp->Nama;
        } else {
            $dt['namapenjamin'] = "";
        }

        $dt['nopenjamin'] = $data['nopenjamin'];
        $dt['norujukan'] = $data['norujukan'];

        $dt['dokter'] = $data['dokter'];
        $getd = $this->mregbooking->getDokter($data['dokter']);
        if ($getd) {
            $dt['namadokter'] = $getd->NamaDokter;
        } else {
            $dt['namadokter'] = "";
        }

        $dt['tanggal'] = $data['tanggal'];
        $dt['waktu'] = $data['waktu'];

        $t = new DateTime($data['tanggal']);

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

        $jdwpoli = $this->mregbooking->getJamPoli($data['bagian'], $data['waktu'], $data['dokter'], $hari);

        $jp = date_format(date_create($jdwpoli->$hari), "H:i");

        $cekbooking = $this->mregbooking->cekBooking($data['idanggotakeluarga'], $data['bagian'], str_replace("-", "", $data['tanggal']), $data['waktu']);
        if ($cekbooking) {
            $this->response([
                'status' => false,
                'message' => 'Maaf pasien ini sudah terdaftar di klinik yang sama pada tanggal yang sama'
            ], 400);
        } else {
            $hitungbooking = $this->mregbooking->hitungBooking(str_replace("-", "", $data['tanggal']));
            $dt['kodebooking'] = str_replace("-", "", $data['tanggal']) . str_pad($hitungbooking + 1, 4, "0", STR_PAD_LEFT);
            $jam = "07:00";
            $time = strtotime($jam);
            $datetime = date("Y-m-d H:i", strtotime($dt['tanggal'] . $jam));

            if ($data['bagian'] == "6101") {
                $hitungpendaftaran = $this->mregbooking->hitungPendaftaranObsgyn(str_replace("-", "", $data['tanggal']), $data['waktu']);
                $hitungpoli = $this->mregbooking->hitungPoli($data['bagian'], str_replace("-", "", $data['tanggal']), $data['waktu']);
                $dt['noantripendaftaran'] = "A" . str_pad($hitungpendaftaran + 1, 4, "0", STR_PAD_LEFT);
                $dt['noantripoli'] = "A" . str_pad($hitungpoli + 1, 4, "0", STR_PAD_LEFT);

                $jml = $hitungpendaftaran;
                $pelayanan = 3;
                $wkt = $jml * $pelayanan;
                $jamdilayani = date("H:i", strtotime('+' . $wkt . ' minutes', $time));
                $dt['jamdilayani'] = date("Y-m-d", strtotime($dt['tanggal'])) . " " . $jamdilayani;
                $dt['datetime'] = $datetime;
            } else {
                $hitungpendaftaran = $this->mregbooking->hitungPendaftaranLain(str_replace("-", "", $data['tanggal']));
                $hitungpoli = $this->mregbooking->hitungPoli($data['bagian'], str_replace("-", "", $data['tanggal']), $data['waktu']);
                $dt['noantripendaftaran'] = "B" . str_pad($hitungpendaftaran + 1, 4, "0", STR_PAD_LEFT);
                $dt['noantripoli'] = str_pad($hitungpoli + 1, 4, "0", STR_PAD_LEFT);

                $jml = $hitungpendaftaran;
                $pelayanan = 3;
                $wkt = $jml * $pelayanan;
                $jamdilayani = date("H:i", strtotime('+' . $wkt . ' minutes', $time));
                $dt['jamdilayani'] = date("Y-m-d", strtotime($dt['tanggal'])) . " " . $jamdilayani;
                $dt['datetime'] = $datetime;
            }

            if ($this->mregbooking->simpanBooking($dt)) {
                $databooking['kodebooking'] = trim($dt['kodebooking']);
                $databooking['antriandaftar'] = trim($dt['noantripendaftaran']);
                $databooking['antrianpoli'] = trim($dt['noantripoli']);
                $databooking['jamdilayani'] = date_format(date_create($dt['jamdilayani']), "H:i");
                $databooking['idanggotakeluarga'] = $data['idanggotakeluarga'];
                $databooking['poli'] = trim($dt['namabagian']);
                $databooking['waktu'] = $dt['waktu'];
                $databooking['jampoli'] = $jp;
                $databooking['tglperiksa'] =  date_format(date_create($data['tanggal']), "d-m-yy");
                $databooking['penjamin'] = trim($dt['namapenjamin']);
                $databooking['nopenjamin'] = trim($dt['nopenjamin']);
                $databooking['norujukan'] = trim($dt['norujukan']);
                $databooking['dokter'] = trim($dt['namadokter']);
                $databooking['tgldaftar'] = date("d-m-Y");
                $this->response([
                    'status' => true,
                    'message' => 'Berhasil Simpan Booking',
                    'data' => $databooking
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Gagal Simpan Booking'
                ], 400);
            }
        }
    }
}
