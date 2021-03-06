<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class UbahTelp extends RestController
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
        $dt['notelp'] = $data['telp'];

        $this->load->model('mubahtelp');
        $cekakun = $this->mubahtelp->cekakun($dt);

        if ($cekakun) {
            $this->mubahtelp->ubahTelp($dt);
            $this->response([
                'status' => true,
                'message' => 'No Telpon berhasil diubah ke ' . $dt['notelp'],
                'user' => $cekakun->NamaAkun,
                'email' => $cekakun->Email,
                'telp' => $dt['notelp']
            ], 200);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Gagal ubah no telpon'
            ], 500);
        }
    }
}
