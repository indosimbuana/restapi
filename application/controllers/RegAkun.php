<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class RegAkun extends RestController
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
        $dt['nama'] = $data['nama'];
        $dt['email'] = $data['email'];
        $dt['telp'] = $data['telp'];
        $dt['password'] = $this->enkripdekrip->proses($data['password']);

        $this->load->model('mregakun');
        $ceknama = $this->mregakun->getAkunByNama($dt['nama']);
        $cektelp = $this->mregakun->getAkunByTelp($dt['telp']);
        $cekemail = $this->mregakun->getAkunByEmail($dt['email']);

        if ($ceknama) {
            $this->response([
                'status' => false,
                'message' => 'Akun dengan Nama ' . $dt['nama'] . ' sudah terdaftar'
            ], 409);
        } else {
            if ($cektelp) {
                $this->response([
                    'status' => false,
                    'message' => 'Akun dengan No Telp ' . $dt['telp'] . ' sudah terdaftar'
                ], 409);
            } else {
                if ($cekemail) {
                    $this->response([
                        'status' => false,
                        'message' => 'Akun dengan Email ' . $dt['email'] . ' sudah terdaftar'
                    ], 409);
                } else {
                    if ($this->mregakun->regAkun($dt)) {
                        $this->response([
                            'status' => true,
                            'message' => 'Registrasi akun berhasil'
                        ], 201);
                    } else {
                        $this->response([
                            'status' => false,
                            'message' => 'Gagal simpan data'
                        ], 500);
                    }
                }
            }
        }
    }
}
