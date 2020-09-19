<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class UbahEmail extends RestController
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
        $this->load->library('enkripdekrip');

        $d = file_get_contents('php://input');
        $data = json_decode($d, true);

        $dt = array();
        $dt['user'] = $data['user'];
        $dt['password'] = $this->enkripdekrip->proses($data['password']);
        $dt['email'] = $data['email'];

        $this->load->model('mubahemail');
        $cekakun = $this->mubahemail->cekAkun($dt);
        $cekemail = $this->mubahemail->cekEmail($dt);

        if ($cekakun) {
            if ($cekemail) {
                $this->response([
                    'status' => false,
                    'message' => 'Email sudah terdaftar'
                ], 500);
            } else {
                $this->mubahemail->ubahEmail($dt);
                $this->response([
                    'status' => true,
                    'message' => 'Email berhasil diubah ke ' . $dt['email'],
                    'user' => $cekakun->NamaAkun,
                    'email' => $dt['email'],
                    'telp' => $cekakun->NoTelpon
                ], 200);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Gagal ubah email'
            ], 500);
        }
    }
}
