<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class RegBooking extends RestController
{

    function __construct()
    {
        parent::__construct();
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
            $dt['namadokter'] = $getd->Nama;
        } else {
            $dt['namadokter'] = "";
        }

        $dt['tanggal'] = $data['tanggal'];
        $dt['waktu'] = $data['waktu'];

        $cekbooking = $this->mregbooking->cekBooking($data['idanggotakeluarga'], $data['bagian'], $data['tanggal'], $data['waktu']);
        if ($cekbooking) {
            $this->response([
                'status' => true,
                'message' => 'Maaf pasien ini sudah terdaftar di klinik yang sama pada tanggal yang sama'
            ], 400);
        } else {
            $hitungbooking = $this->mregbooking->hitungBooking($data['tanggal']);
            $dt['kodebooking'] = str_replace("-", "", $data['tanggal']) . str_pad($hitungbooking + 1, 4, "0", STR_PAD_LEFT);

            if ($data['bagian'] == "6101") {
                $hitungpendaftaran = $this->mregbooking->hitungPendaftaranObsgyn($data['tanggal'], $data['waktu']);
                $hitungpoli = $this->mregbooking->hitungPoli($data['bagian'], $data['tanggal'], $data['waktu']);
                $dt['noantripendaftaran'] = "A" . str_pad($hitungpendaftaran + 1, 4, "0", STR_PAD_LEFT);
                $dt['noantripoli'] = "A" . str_pad($hitungpoli + 1, 4, "0", STR_PAD_LEFT);

                $time = strtotime('07:00');
                $jml = $hitungpendaftaran;
                $pelayanan = 5;
                $wkt = $jml * $pelayanan;
                $jamdilayani = date("H:i", strtotime('+' . $wkt . ' minutes', $time));
                $dt['jamdilayani'] = $jamdilayani;
            } else {
                $hitungpendaftaran = $this->mregbooking->hitungPendaftaranLain($data['tanggal']);
                $hitungpoli = $this->mregbooking->hitungPoli($data['bagian'], $data['tanggal'], $data['waktu']);
                $dt['noantripendaftaran'] = "B" . str_pad($hitungpendaftaran + 1, 4, "0", STR_PAD_LEFT);
                $dt['noantripoli'] = str_pad($hitungpoli + 1, 4, "0", STR_PAD_LEFT);

                $time = strtotime('07:00');
                $jml = $hitungpendaftaran;
                $pelayanan = 5;
                $wkt = $jml * $pelayanan;
                $jamdilayani = date("H:i", strtotime('+' . $wkt . ' minutes', $time));
                $dt['jamdilayani'] = $jamdilayani;
            }

            if ($this->mregbooking->simpanBooking($dt)) {
                $this->response([
                    'kodebooking' => $dt['kodebooking'],
                    'noantripendaftaran' => $dt['noantripendaftaran'],
                    'noantripoli' => $dt['noantripoli'],
                    'jamdilayani' => $dt['jamdilayani'],
                    'namapoli' => $dt['namabagian'],
                    'namadokter' => $dt['namadokter'],
                    'tanggal' => $dt['tanggal'],
                    'waktu' => $dt['waktu'],
                    'namapenjamin' => $dt['namapenjamin'],
                    'nopenjamin' => $dt['nopenjamin'],
                    'norujukan' => $dt['norujukan']
                ], 200);
            } else {
                $this->response([
                    'status' => true,
                    'message' => 'Gagal Simpan Booking'
                ], 400);
            }
        }
    }
}
