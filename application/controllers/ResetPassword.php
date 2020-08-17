<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Resetpassword extends RestController
{

    function __construct()
    {
        parent::__construct();
    }

    public function index_post()
    {
        $this->load->library('enkripdekrip');

        $d = file_get_contents('php://input');
        $data = json_decode($d, true);

        $dt = array();
        $dt['telp'] = $data['telp'];
        $dt['kode'] = $data['kode'];
        $dt['passwordbaru'] = $this->enkripdekrip->proses($data['passwordbaru']);

        $this->load->model('mresetpassword');
        $cek = $this->mresetpassword->cekKode($dt);

        if ($cek) {
            if ($this->mresetpassword->ubahPassword($dt)) {
                $this->mresetpassword->ubahReset($dt);
                $this->response([
                    'status' => true,
                    'message' => 'Password berhasil diubah',
                    'user' => $cek->NamaAkun,
                    'email' => $cek->Email,
                    'telp' => $cek->NoTelpon
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Gagal ubah password'
                ], 500);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Kode salah atau sudah tidak berlaku lagi'
            ], 404);
        }
    }
}
