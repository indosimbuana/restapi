<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Ubahpassword extends RestController
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
        $dt['user'] = $data['user'];
        $dt['password'] = $this->enkripdekrip->proses($data['password']);
        $dt['passwordbaru'] = $this->enkripdekrip->proses($data['passwordbaru']);

        $this->load->model('mubahpassword');
        $cekakun = $this->mubahpassword->cekakun($dt);

        if ($cekakun) {
            $this->mubahpassword->ubahPassword($dt);
            $this->response([
                'status' => true,
                'message' => 'Password berhasil diubah',
                'user' => $cekakun->NamaAkun,
                'email' => $cekakun->Email,
                'telp' => $cekakun->NoTelpon
            ], 200);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Gagal ubah password'
            ], 500);
        }
    }
}
