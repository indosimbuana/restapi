<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Anggotakeluarga extends RestController
{

    function __construct()
    {
        parent::__construct();
    }

    public function index_get()
    {
        $id = $this->get('id');
        $akun = $this->get('akun');

        $this->load->model('manggotakeluarga');
        if ($id === null) {
            if ($akun === null || $akun === '') {
                $this->response([
                    'status' => false,
                    'message' => 'ID Akun kosong'
                ], 404);
            } else {
                $a = $this->manggotakeluarga->getAnggotaAkun($akun);
                if ($a) {
                    $n = 0;
                    foreach ($a as $dt) {
                        $data[$n]['idanggota'] = $dt['idAnggotaKeluarga'];
                        $data[$n]['hubungan'] = trim($dt['hubunganAkun']);
                        $data[$n]['nopasien'] = trim($dt['noPasien']);
                        $data[$n]['jnskelamin'] = trim($dt['JenisKelamin']);
                        $data[$n]['tgllahir'] = trim($dt['TglLahir']);
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
                        'message' => 'No anggota were found'
                    ], 404);
                }
            }
        } else {
            $da = $this->manggotakeluarga->getAnggotaById($id);
            if ($da) {
                $data['idanggota'] = $da->idAnggotaKeluarga;
                $data['hubungan'] = trim($da->hubunganAkun);
                $data['nopasien'] = trim($da->noPasien);
                $data['jnskelamin'] = trim($da->JenisKelamin);
                $data['tgllahir'] = trim($da->TglLahir);
                $this->response([
                    'status' => true,
                    'message' => 'Data found',
                    'data' => $data
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No anggota were found'
                ], 404);
            }
        }
    }
}
