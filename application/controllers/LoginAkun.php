<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class LoginAkun extends RestController
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

        $this->load->model('mlogin');
        $cek = $this->mlogin->cekAkun($dt);

        if ($cek) {
            $this->response([
                'status' => true,
                'message' => 'Login berhasil',
                'user' => $cek->NamaAkun,
                'email' => $cek->Email,
                'telp' => $cek->NoTelpon,
                'level' => $cek->LevelAkun
            ], 200);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Informasi akun tidak ditemukan'
            ], 404);
        }
    }
}
