<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Cekpasienlama extends RestController
{

    function __construct()
    {
        parent::__construct();
    }

    public function index_post()
    {
        $d = file_get_contents('php://input');
        $data = json_decode($d, true);

        $dt = array();
        $dt['nopasien'] = $data['nopasien'];
        $dt['tgllahir'] = $data['tgllahir'];

        $this->load->model('mcekpasienlama');
        $cek = $this->mcekpasienlama->cekPasien($dt);

        if ($cek) {
            $this->response([
                'status' => true,
                'message' => 'Pasien ditemukan',
                'nama' => $cek->NamaPasien,
                'nopasien' => $cek->Nopasien,
                'tgllahir' => $cek->TglLahir,
                'jnskel' => $cek->JenisKelamin,
                'alamat' => $cek->AlamatPasien
            ], 200);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Informasi pasien tidak ditemukan'
            ], 404);
        }
    }
}
