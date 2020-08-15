<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class UbahEmail extends RestController
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
        $dt['email'] = $data['email'];

        $this->load->model('mubahemail');
        $cekakun = $this->mubahemail->cekakun($dt);

        if ($cekakun) {
            $this->mubahemail->ubahEmail($dt);
            $this->response([
                'status' => true,
                'message' => 'Email berhasil diubah ke ' . $dt['email']
            ], 200);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Gagal ubah email'
            ], 500);
        }
    }
}
