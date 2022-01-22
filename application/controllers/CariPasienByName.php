<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class CariPasienByName extends RestController
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
        $d = file_get_contents('php://input');
        $data = json_decode($d, true);

        $dt = array();
        $dt['nama'] = $data['nama'];
        $dt['tgllahir'] = $data['tgllahir'];

        $this->load->model('mcekpasienlama');
        $cek = $this->mcekpasienlama->cariPasienByName($dt);

        if ($cek) {
            $this->response([
                'status' => true,
                'message' => 'Pasien ditemukan',
                'nama' => $cek->NamaPasien,
                'nopasien' => $cek->Nopasien,
                'tgllahir' => $cek->TglLahir,
                'jnskel' => $cek->JenisKelamin,
                'alamat' => $cek->AlamatPasien,
                'telp' => trim($cek->TlpPasien),
                'email' => '-'
            ], 200);
        } else {
            $this->load->model('mcekpasienserverlama');
            $cekserverlama = $this->mcekpasienserverlama->cariPasienByName($dt);
            if ($cekserverlama) {
                $this->response([
                    'status' => true,
                    'message' => 'Pasien ditemukan',
                    'nama' => $cekserverlama->NamaPasien,
                    'nopasien' => $cekserverlama->Nopasien,
                    'tgllahir' => $cekserverlama->TglLahir,
                    'jnskel' => $cekserverlama->JenisKelamin,
                    'alamat' => $cekserverlama->AlamatPasien,
                    'telp' => trim($cekserverlama->TlpPasien),
                    'email' => '-'
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Informasi pasien tidak ditemukan'
                ], 404);
            }
        }
    }
}
