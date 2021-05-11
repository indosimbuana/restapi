<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Jadwalpoli extends RestController
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

    public function index_get()
    {
        $id = $this->get('id');

        $this->load->model('mjadwalpoli');

        if ($id == NULL) {
            $pol = $this->mjadwalpoli->getPoli();
            if ($pol) {
                $n = 0;
                foreach ($pol as $p) {
                    $data[$n]['kodeklinik'] = $p['KodeKlinik'];
                    $data[$n]['namaklinik'] = ucwords($p['NamaBagian']);
                    $data[$n]['jnswkt'] = $p['JenisWaktu'];
                    $data[$n]['dokter'] = ucwords($p['NamaDokter']);
                    $data[$n]['kodedokter'] = $p['KodeDokter'];
                    $data[$n]['senin'] = $p['Senin'] == NULL ||  $p['Senin'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['Senin']), "H:i");
                    $data[$n]['senintutup'] = $p['SeninTutup'] == NULL ||  $p['SeninTutup'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['SeninTutup']), "H:i");
                    $data[$n]['selasa'] = $p['Selasa'] == NULL ||  $p['Selasa'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['Selasa']), "H:i");
                    $data[$n]['selasatutup'] = $p['SelasaTutup'] == NULL ||  $p['SelasaTutup'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['SelasaTutup']), "H:i");
                    $data[$n]['rabu'] = $p['Rabu'] == NULL ||  $p['Rabu'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['Rabu']), "H:i");
                    $data[$n]['rabututup'] = $p['RabuTutup'] == NULL ||  $p['RabuTutup'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['RabuTutup']), "H:i");
                    $data[$n]['kamis'] = $p['Kamis'] == NULL ||  $p['Kamis'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['Kamis']), "H:i");
                    $data[$n]['kamistutup'] = $p['KamisTutup'] == NULL ||  $p['KamisTutup'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['KamisTutup']), "H:i");
                    $data[$n]['jumat'] = $p['Jumat'] == NULL ||  $p['Jumat'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['Jumat']), "H:i");
                    $data[$n]['jumattutup'] = $p['JumatTutup'] == NULL ||  $p['JumatTutup'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['JumatTutup']), "H:i");
                    $data[$n]['sabtu'] = $p['Sabtu'] == NULL ||  $p['Sabtu'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['Sabtu']), "H:i");
                    $data[$n]['sabtututup'] = $p['SabtuTutup'] == NULL ||  $p['Sabtu'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['SabtuTutup']), "H:i");
                    $data[$n]['minggu'] = $p['Minggu'] == NULL ||  $p['Minggu'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['Minggu']), "H:i");
                    $data[$n]['minggututup'] = $p['MingguTutup'] == NULL ||  $p['MingguTutup'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['MingguTutup']), "H:i");
                    $data[$n]['bukadaftar'] = date_format(date_create($p['JamBukaPendaftaran']), "H:i");
                    $data[$n]['tutupdaftar'] = date_format(date_create($p['JamTutupPendaftaran']), "H:i");
                    $data[$n]['ket'] = ucwords($p['Keterangan']);
                    $n++;
                }
                $this->response([
                    'status' => true,
                    'message' => 'Data found',
                    'data' => $data
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No poli were found'
                ], 404);
            }
        } else {
            $pol = $this->mjadwalpoli->getPoliById($id);
            if ($pol) {
                $n = 0;
                foreach ($pol as $p) {
                    $data[$n]['kodeklinik'] = $p['KodeKlinik'];
                    $data[$n]['namaklinik'] = ucwords($p['NamaBagian']);
                    $data[$n]['jnswkt'] = $p['JenisWaktu'];
                    $data[$n]['dokter'] = ucwords($p['NamaDokter']);
                    $data[$n]['kodedokter'] = $p['KodeDokter'];
                    $data[$n]['senin'] = $p['Senin'] == NULL ||  $p['Senin'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['Senin']), "H:i");
                    $data[$n]['senintutup'] = $p['SeninTutup'] == NULL ||  $p['SeninTutup'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['SeninTutup']), "H:i");
                    $data[$n]['selasa'] = $p['Selasa'] == NULL ||  $p['Selasa'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['Selasa']), "H:i");
                    $data[$n]['selasatutup'] = $p['SelasaTutup'] == NULL ||  $p['SelasaTutup'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['SelasaTutup']), "H:i");
                    $data[$n]['rabu'] = $p['Rabu'] == NULL ||  $p['Rabu'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['Rabu']), "H:i");
                    $data[$n]['rabututup'] = $p['RabuTutup'] == NULL ||  $p['RabuTutup'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['RabuTutup']), "H:i");
                    $data[$n]['kamis'] = $p['Kamis'] == NULL ||  $p['Kamis'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['Kamis']), "H:i");
                    $data[$n]['kamistutup'] = $p['KamisTutup'] == NULL ||  $p['KamisTutup'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['KamisTutup']), "H:i");
                    $data[$n]['jumat'] = $p['Jumat'] == NULL ||  $p['Jumat'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['Jumat']), "H:i");
                    $data[$n]['jumattutup'] = $p['JumatTutup'] == NULL ||  $p['JumatTutup'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['JumatTutup']), "H:i");
                    $data[$n]['sabtu'] = $p['Sabtu'] == NULL ||  $p['Sabtu'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['Sabtu']), "H:i");
                    $data[$n]['sabtututup'] = $p['SabtuTutup'] == NULL ||  $p['Sabtu'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['SabtuTutup']), "H:i");
                    $data[$n]['minggu'] = $p['Minggu'] == NULL ||  $p['Minggu'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['Minggu']), "H:i");
                    $data[$n]['minggututup'] = $p['MingguTutup'] == NULL ||  $p['MingguTutup'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['MingguTutup']), "H:i");
                    $data[$n]['bukadaftar'] = date_format(date_create($p['JamBukaPendaftaran']), "H:i");
                    $data[$n]['tutupdaftar'] = date_format(date_create($p['JamTutupPendaftaran']), "H:i");
                    $data[$n]['ket'] = ucwords($p['Keterangan']);
                    $n++;
                }
                $this->response([
                    'status' => true,
                    'message' => 'Data found',
                    'data' => $data
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No poli were found'
                ], 404);
            }
        }
    }

    public function index_post()
    {
        $d = file_get_contents('php://input');
        $data = json_decode($d, true);

        $date = date('Y-m-d H:i');

        $dt = array();
        $dt['KodeKlinik'] = $data['KodeKlinik'] == '' ? NULL : $data['KodeKlinik'];
        $dt['JenisWaktu'] = $data['JenisWaktu'] == '' ? NULL : $data['JenisWaktu'];
        $dt['KodeDokter'] = $data['KodeDokter']  == '' ? NULL : $data['KodeDokter'];
        $dt['Senin'] = $data['Senin']  == '' ? NULL : date_format(date_create($data['Senin']), "H:i:s");
        $dt['SeninTutup'] = $data['SeninTutup']  == '' ? NULL : date_format(date_create($data['SeninTutup']), "H:i:s");
        $dt['Selasa'] = $data['Selasa']  == '' ? NULL : date_format(date_create($data['Selasa']), "H:i:s");
        $dt['SelasaTutup'] = $data['SelasaTutup']  == '' ? NULL : date_format(date_create($data['SelasaTutup']), "H:i:s");
        $dt['Rabu'] = $data['Rabu']  == '' ? NULL : date_format(date_create($data['Rabu']), "H:i:s");
        $dt['RabuTutup'] = $data['RabuTutup']  == '' ? NULL : date_format(date_create($data['RabuTutup']), "H:i:s");
        $dt['Kamis'] = $data['Kamis']  == '' ? NULL : date_format(date_create($data['Kamis']), "H:i:s");
        $dt['KamisTutup'] = $data['KamisTutup']  == '' ? NULL : date_format(date_create($data['KamisTutup']), "H:i:s");
        $dt['Jumat'] = $data['Jumat']  == '' ? NULL : date_format(date_create($data['Jumat']), "H:i:s");
        $dt['JumatTutup'] = $data['JumatTutup']  == '' ? NULL : date_format(date_create($data['JumatTutup']), "H:i:s");
        $dt['Sabtu'] = $data['Sabtu']  == '' ? NULL : date_format(date_create($data['Sabtu']), "H:i:s");
        $dt['SabtuTutup'] = $data['SabtuTutup']  == '' ? NULL : date_format(date_create($data['SabtuTutup']), "H:i:s");
        $dt['Minggu'] = $data['Minggu']  == '' ? NULL : date_format(date_create($data['Minggu']), "H:i:s");
        $dt['MingguTutup'] = $data['MingguTutup']  == '' ? NULL : date_format(date_create($data['MingguTutup']), "H:i:s");
        $dt['JamBukaPendaftaran'] = $data['JamBukaPendaftaran']  == '' ? NULL : date_format(date_create($data['JamBukaPendaftaran']), "H:i:s");
        $dt['JamTutupPendaftaran'] = $data['JamTutupPendaftaran']  == '' ? NULL : date_format(date_create($data['JamTutupPendaftaran']), "H:i:s");
        $dt['Keterangan'] = $data['Keterangan']  == '' ? NULL : $data['Keterangan'];
        $dt['UserUpdate'] = $data['UserUpdate']  == '' ? NULL : $data['UserUpdate'];
        $dt['UpdateTime'] = $date;

        $this->load->model('mjadwalpoli');

        if (($data['KodeKlinik'] == NULL || $data['KodeKlinik'] == '') && ($data['JenisWaktu'] == NULL || $data['JenisWaktu'] == '') && ($data['KodeDokter'] == NULL || $data['KodeDokter'] == '')) {
            $this->response([
                'status' => false,
                'message' => 'Gagal simpan Jadwal Poli'
            ], 400);
        } else {
            $cek = $this->mjadwalpoli->cekJadwalPoli($dt);
            if ($cek) {
                $this->response([
                    'status' => false,
                    'message' => 'Gagal simpan Jadwal Poli, Jadwal sudah ada'
                ], 400);
            } else {
                if ($this->mjadwalpoli->simpanJadwalPoli($dt)) {
                    $this->response([
                        'status' => true,
                        'message' => 'Jadwal Poli berhasil tersimpan'
                    ], 200);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Gagal simpan Jadwal Poli'
                    ], 400);
                }
            }
        }
    }

    function index_put()
    {
        $d = file_get_contents('php://input');
        $data = json_decode($d, true);

        $date = date('Y-m-d H:i:s');

        $dt = array();
        $dt['KodeKlinik'] = $data['KodeKlinik'] == '' ? NULL : $data['KodeKlinik'];
        $dt['JenisWaktu'] = $data['JenisWaktu'] == '' ? NULL : $data['JenisWaktu'];
        $dt['KodeDokter'] = $data['KodeDokter']  == '' ? NULL : $data['KodeDokter'];
        $dt['Senin'] = $data['Senin']  == '' ? NULL : date_format(date_create($data['Senin']), "H:i:s");
        $dt['SeninTutup'] = $data['SeninTutup']  == '' ? NULL : date_format(date_create($data['SeninTutup']), "H:i:s");
        $dt['Selasa'] = $data['Selasa']  == '' ? NULL : date_format(date_create($data['Selasa']), "H:i:s");
        $dt['SelasaTutup'] = $data['SelasaTutup']  == '' ? NULL : date_format(date_create($data['SelasaTutup']), "H:i:s");
        $dt['Rabu'] = $data['Rabu']  == '' ? NULL : date_format(date_create($data['Rabu']), "H:i:s");
        $dt['RabuTutup'] = $data['RabuTutup']  == '' ? NULL : date_format(date_create($data['RabuTutup']), "H:i:s");
        $dt['Kamis'] = $data['Kamis']  == '' ? NULL : date_format(date_create($data['Kamis']), "H:i:s");
        $dt['KamisTutup'] = $data['KamisTutup']  == '' ? NULL : date_format(date_create($data['KamisTutup']), "H:i:s");
        $dt['Jumat'] = $data['Jumat']  == '' ? NULL : date_format(date_create($data['Jumat']), "H:i:s");
        $dt['JumatTutup'] = $data['JumatTutup']  == '' ? NULL : date_format(date_create($data['JumatTutup']), "H:i:s");
        $dt['Sabtu'] = $data['Sabtu']  == '' ? NULL : date_format(date_create($data['Sabtu']), "H:i:s");
        $dt['SabtuTutup'] = $data['SabtuTutup']  == '' ? NULL : date_format(date_create($data['SabtuTutup']), "H:i:s");
        $dt['Minggu'] = $data['Minggu']  == '' ? NULL : date_format(date_create($data['Minggu']), "H:i:s");
        $dt['MingguTutup'] = $data['MingguTutup']  == '' ? NULL : date_format(date_create($data['MingguTutup']), "H:i:s");
        $dt['JamBukaPendaftaran'] = $data['JamBukaPendaftaran']  == '' ? NULL : date_format(date_create($data['JamBukaPendaftaran']), "H:i:s");
        $dt['JamTutupPendaftaran'] = $data['JamTutupPendaftaran']  == '' ? NULL : date_format(date_create($data['JamTutupPendaftaran']), "H:i:s");
        $dt['Keterangan'] = $data['Keterangan']  == '' ? NULL : $data['Keterangan'];
        $dt['UserUpdate'] = $data['UserUpdate']  == '' ? NULL : $data['UserUpdate'];
        $dt['UpdateTime'] = $date;

        $this->load->model('mjadwalpoli');

        if (($data['KodeKlinik'] == NULL || $data['KodeKlinik'] == '') && ($data['JenisWaktu'] == NULL || $data['JenisWaktu'] == '') && ($data['KodeDokter'] == NULL || $data['KodeDokter'] == '')) {
            $this->response([
                'status' => false,
                'message' => 'Gagal update Jadwal Poli'
            ], 400);
        } else {
            $cek = $this->mjadwalpoli->cekJadwalPoli($dt);
            if ($cek) {
                if ($this->mjadwalpoli->updateJadwalPoli($dt)) {
                    $this->response([
                        'status' => true,
                        'message' => 'Jadwal Poli berhasil diupdate'
                    ], 200);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Gagal update Jadwal Poli'
                    ], 400);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Gagal update Jadwal Poli, Jadwal tidak ditemukan'
                ], 400);
            }
        }
    }

    function index_delete()
    {
        $d = file_get_contents('php://input');
        $data = json_decode($d, true);

        $dt['KodeKlinik'] = $data['poli'];
        $dt['JenisWaktu'] = $data['waktu'];
        $dt['KodeDokter'] = $data['dokter'];

        $this->load->model('mjadwalpoli');
        $delete = $this->mjadwalpoli->hapusJadwalPoli($dt);
        if ($delete) {
            $this->response(array('status' => true), 200);
        } else {
            $this->response(array('status' => false, 502));
        }
    }
}
