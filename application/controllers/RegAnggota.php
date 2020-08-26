<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Reganggota extends RestController
{

    function __construct()
    {
        parent::__construct();
    }

    public function index_post()
    {
        $d = file_get_contents('php://input');
        $data = json_decode($d, true);

        $date = date('yymd');

        $dt = array();
        $dt['idanggotakeluarga'] = $date . rand(1000, 10000);
        $dt['idakun'] = $data['idakun'];
        $dt['hubungan'] = $data['hubungan'];
        $dt['nopasien'] = $data['nopasien'];
        $dt['ktp'] = $data['ktp'];
        $dt['jeniskelamin'] = $data['jeniskelamin'];
        $dt['tempatlahir'] = $data['tempatlahir'];
        $dt['tgllahir'] = $data['tgllahir'];
        $dt['alamat'] = $data['alamat'];
        $dt['provinsi'] = $data['provinsi'];
        $dt['kabupaten'] = $data['kabupaten'];
        $dt['kecamatan'] = $data['kecamatan'];
        $dt['kodepos'] = $data['kodepos'];
        $dt['agama'] = $data['agama'];
        $dt['goloangandarah'] = $data['goloangandarah'];
        $dt['pendidikan'] = $data['pendidikan'];
        $dt['statuskawin'] = $data['statuskawin'];
        $dt['pekerjaan'] = $data['pekerjaan'];
        $dt['wni'] = $data['wni'];
        $dt['negara'] = $data['negara'];
        $dt['suku'] = $data['suku'];
        $dt['bahasa'] = $data['bahasa'];
        $dt['alergi'] = $data['alergi'];
        $dt['alamatkantor'] = $data['alamatkantor'];
        $dt['telpkantor'] = $data['telpkantor'];
        $dt['namakeluarga'] = $data['namakeluarga'];
        $dt['namaayah'] = $data['namaayah'];
        $dt['namaibu'] = $data['namaibu'];
        $dt['namasuamiistri'] = $data['namasuamiistri'];
        $dt['notelpon'] = $data['notelpon'];
        $dt['email'] = $data['email'];

        $this->load->model('mreganggota');

        if ($this->mreganggota->getAkunByNama($data['idakun'])) {
            if ($data['nopasien'] == '' || $data['nopasien'] == null) {
                // Pasien Baru
                if ($this->mreganggota->getAnggotaPasienBaru($data['idakun'], $data['ktp'])) {
                    $this->response([
                        'status' => true,
                        'message' => 'Gagal Simpan Pasien Baru, anggota keluarga sudah terdaftar'
                    ], 400);
                } else {
                    $dt['namalengkap'] = $data['namalengkap'];
                    if ($this->mreganggota->simpanPasienBaru($dt)) {
                        $pb['idanggotakeluarga'] = $dt['idanggotakeluarga'];
                        $pb['namalengkap'] = $data['namalengkap'];
                        $this->response([
                            'status' => true,
                            'message' => 'Berhasil Simpan Pasien Baru',
                            'data' => $pb
                        ], 200);
                    } else {
                        $this->response([
                            'status' => true,
                            'message' => 'Gagal Simpan Pasien Baru'
                        ], 400);
                    }
                }
            } else {
                // Pasien Lama
                if ($this->mreganggota->getAnggotaPasienLama($data['idakun'], $data['nopasien'])) {
                    $this->response([
                        'status' => true,
                        'message' => 'Gagal Simpan Pasien Lama, anggota keluarga sudah terdaftar'
                    ], 400);
                } else {
                    $pslama = $this->mreganggota->getPasienLama($data['nopasien']);
                    if ($pslama) {
                        $dt['namalengkap'] = $pslama->NamaPasien;
                        if ($this->mreganggota->simpanPasienLama($dt)) {
                            $pl['idanggotakeluarga'] = $dt['idanggotakeluarga'];
                            $pl['namalengkap'] = $pslama->NamaPasien;
                            $this->response([
                                'status' => true,
                                'message' => 'Berhasil Simpan Pasien Lama',
                                'data' => $pl
                            ], 200);
                        } else {
                            $this->response([
                                'status' => true,
                                'message' => 'Gagal Simpan Pasien Lama'
                            ], 400);
                        }
                    } else {
                        $this->response([
                            'status' => true,
                            'message' => 'Gagal Simpan, data pasien lama tidak ditemukan'
                        ], 400);
                    }
                }
            }
        } else {
            $this->response([
                'status' => true,
                'message' => 'Gagal Simpan, akun tidak terdaftar'
            ], 400);
        }
    }
}
