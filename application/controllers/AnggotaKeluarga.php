<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Anggotakeluarga extends RestController
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
        $id = $this->get('id');
        $akun = $this->get('akun');

        $this->load->model('manggotakeluarga');
        $this->load->model('mcekpasienlama');
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
                        if ($dt['noPasien'] == '') {
                            $data[$n]['nopasien'] = trim($dt['noPasien']);
                            $data[$n]['namalengkap'] = ucwords($dt['NamaLengkap']);
                            $data[$n]['jnskelamin'] = trim($dt['JenisKelamin']);
                            $data[$n]['tgllahir'] = trim($dt['TglLahir']);
                        } else {
                            $gp = $this->mcekpasienlama->getPasien($dt['noPasien']);
                            $data[$n]['nopasien'] = trim($dt['noPasien']);
                            $data[$n]['namalengkap'] = ucwords($gp->NamaPasien);
                            $data[$n]['jnskelamin'] = trim($gp->JenisKelamin);
                            $data[$n]['tgllahir'] = trim($gp->TglLahir);
                        }
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
                if ($da->noPasien == '') {
                    $data['nopasien'] = trim($da->noPasien);
                    $data['namalengkap'] = ucwords($da->NamaLengkap);
                    $data['jnskelamin'] = trim($da->JenisKelamin);
                    $data['tgllahir'] = trim($da->TglLahir);
                } else {
                    $gp = $this->mcekpasienlama->getPasien($da->noPasien);
                    $data['nopasien'] = trim($da->noPasien);
                    $data['namalengkap'] = ucwords($gp->NamaPasien);
                    $data['jnskelamin'] = trim($gp->JenisKelamin);
                    $data['tgllahir'] = trim($gp->TglLahir);
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
    }
}
