<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Riwayatreg extends RestController
{

    function __construct()
    {
        parent::__construct();
    }

    public function index_get()
    {
        $id = $this->get('kode');
        $ag = $this->get('anggota');

        $this->load->model('mriwayatreg');
        if ($id === null) {
            if ($ag === null || $ag === '') {
                $this->response([
                    'status' => false,
                    'message' => 'ID Akun kosong'
                ], 404);
            } else {
                $a = $this->mriwayatreg->getRiwayatReg($ag);
                if ($a) {
                    $n = 0;
                    foreach ($a as $dt) {
                        $data[$n]['kodebooking'] = trim($dt['kodeBooking']);
                        $data[$n]['idanggotakeluarga'] = trim($dt['idAnggotaKeluarga']);
                        $data[$n]['poli'] = trim($dt['namaBagian']);
                        $data[$n]['waktu'] = trim($dt['waktuPemeriksaan']);
                        $data[$n]['tglperiksa'] = date_format(date_create($dt['tglPemeriksaan']), "d-m-yy");
                        $n++;
                    }
                    $this->response([
                        'status' => true,
                        'message' => 'Data found',
                        'data' => $data
                    ], 200);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'No booking were found'
                    ], 404);
                }
            }
        } else {
            $da = $this->mriwayatreg->getRiwayatById($id);
            if ($da) {
                $data['kodebooking'] = trim($da->kodeBooking);
                $data['antriandaftar'] = trim($da->noAntrianPendaftaran);
                $data['antrianpoli'] = trim($da->noAntrianKlinik);
                $data['jamdilayani'] = trim($da->jamDilayani);
                $data['idanggotakeluarga'] = trim($da->idAnggotaKeluarga);
                $data['poli'] = trim($da->namaBagian);
                $data['waktu'] = trim($da->waktuPemeriksaan);
                $data['tglperiksa'] = date_format(date_create($da->tglPemeriksaan), "d-m-yy");
                $data['penjamin'] = trim($da->namaPenjamin);
                $data['nopenjamin'] = trim($da->noPenjamin);
                $data['norujukan'] = trim($da->noRujukan);
                $data['dokter'] = trim($da->namaDokter);
                $data['tgldaftar'] = date_format(date_create($da->dateEntry), "d-m-yy");
                $this->response([
                    'status' => true,
                    'message' => 'Data found',
                    'data' => $data
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No booking were found'
                ], 404);
            }
        }
    }
}
