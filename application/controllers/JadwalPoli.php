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
                    $data[$n]['namaklinik'] = $p['NamaBagian'];
                    $data[$n]['jnswkt'] = $p['JenisWaktu'];
                    $data[$n]['dokter'] = $p['NamaDokter'];
                    $data[$n]['kodedokter'] = $p['KodeDokter'];
                    $data[$n]['senin'] = $p['Senin'] == NULL ||  $p['Senin'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['Senin']), "H:s");
                    $data[$n]['senintutup'] = $p['SeninTutup'] == NULL ||  $p['SeninTutup'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['SeninTutup']), "H:s");
                    $data[$n]['selasa'] = $p['Selasa'] == NULL ||  $p['Selasa'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['Selasa']), "H:s");
                    $data[$n]['selasatutup'] = $p['SelasaTutup'] == NULL ||  $p['SelasaTutup'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['SelasaTutup']), "H:s");
                    $data[$n]['rabu'] = $p['Rabu'] == NULL ||  $p['Rabu'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['Rabu']), "H:s");
                    $data[$n]['rabututup'] = $p['RabuTutup'] == NULL ||  $p['RabuTutup'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['RabuTutup']), "H:s");
                    $data[$n]['kamis'] = $p['Kamis'] == NULL ||  $p['Kamis'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['Kamis']), "H:s");
                    $data[$n]['kamistutup'] = $p['KamisTutup'] == NULL ||  $p['KamisTutup'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['KamisTutup']), "H:s");
                    $data[$n]['jumat'] = $p['Jumat'] == NULL ||  $p['Jumat'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['Jumat']), "H:s");
                    $data[$n]['jumattutup'] = $p['JumatTutup'] == NULL ||  $p['JumatTutup'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['JumatTutup']), "H:s");
                    $data[$n]['sabtu'] = $p['Sabtu'] == NULL ||  $p['Sabtu'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['Sabtu']), "H:s");
                    $data[$n]['sabtututup'] = $p['SabtuTutup'] == NULL ? "SabtuTutup" : date_format(date_create($p['SabtuTutup']), "H:s");
                    $data[$n]['minggu'] = $p['Minggu'] == NULL ||  $p['Minggu'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['Minggu']), "H:s");
                    $data[$n]['minggututup'] = $p['MingguTutup'] == NULL ||  $p['MingguTutup'] == "00:00:00.00000" ? "Libur" : date_format(date_create($p['MingguTutup']), "H:s");
                    $data[$n]['bukadaftar'] = date_format(date_create($p['JamBukaPendaftaran']), "H:s");
                    $data[$n]['tutupdaftar'] = date_format(date_create($p['JamTutupPendaftaran']), "H:s");
                    $data[$n]['ket'] = $p['Keterangan'];
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
                    $data[$n]['namaklinik'] = $p['NamaBagian'];
                    $data[$n]['jnswkt'] = $p['JenisWaktu'];
                    $data[$n]['dokter'] = $p['NamaDokter'];
                    $data[$n]['kodedokter'] = $p['KodeDokter'];
                    $data[$n]['senin'] = $p['Senin'] == NULL ? "Libur" : date_format(date_create($p['Senin']), "H:s");
                    $data[$n]['senintutup'] = $p['SeninTutup'] == NULL ? "Libur" : date_format(date_create($p['SeninTutup']), "H:s");
                    $data[$n]['selasa'] = $p['Selasa'] == NULL ? "Libur" : date_format(date_create($p['Selasa']), "H:s");
                    $data[$n]['selasatutup'] = $p['SelasaTutup'] == NULL ? "Libur" : date_format(date_create($p['SelasaTutup']), "H:s");
                    $data[$n]['rabu'] = $p['Rabu'] == NULL ? "Libur" : date_format(date_create($p['Rabu']), "H:s");
                    $data[$n]['rabututup'] = $p['RabuTutup'] == NULL ? "Libur" : date_format(date_create($p['RabuTutup']), "H:s");
                    $data[$n]['kamis'] = $p['Kamis'] == NULL ? "Libur" : date_format(date_create($p['Kamis']), "H:s");
                    $data[$n]['kamistutup'] = $p['KamisTutup'] == NULL ? "Libur" : date_format(date_create($p['KamisTutup']), "H:s");
                    $data[$n]['jumat'] = $p['Jumat'] == NULL ? "Libur" : date_format(date_create($p['Jumat']), "H:s");
                    $data[$n]['jumattutup'] = $p['JumatTutup'] == NULL ? "Libur" : date_format(date_create($p['JumatTutup']), "H:s");
                    $data[$n]['sabtu'] = $p['Sabtu'] == NULL ? "Libur" : date_format(date_create($p['Sabtu']), "H:s");
                    $data[$n]['sabtututup'] = $p['SabtuTutup'] == NULL ? "Libur" : date_format(date_create($p['SabtuTutup']), "H:s");
                    $data[$n]['minggu'] = $p['Minggu'] == NULL ? "Libur" : date_format(date_create($p['Minggu']), "H:s");
                    $data[$n]['minggututup'] = $p['MingguTutup'] == NULL ? "Libur" : date_format(date_create($p['MingguTutup']), "H:s");
                    $data[$n]['bukadaftar'] = date_format(date_create($p['JamBukaPendaftaran']), "H:s");
                    $data[$n]['tutupdaftar'] = date_format(date_create($p['JamTutupPendaftaran']), "H:s");
                    $data[$n]['ket'] = $p['Keterangan'];
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

        $date = date('Y-m-d H:i:s');

        $dt = array();
        $dt['KodeKlinik'] = $data['KodeKlinik'] == '' ? NULL : $data['KodeKlinik'];
        $dt['JenisWaktu'] = $data['JenisWaktu'] == '' ? NULL : $data['JenisWaktu'];
        $dt['KodeDokter'] = $data['KodeDokter']  == '' ? NULL : $data['KodeDokter'];
        $dt['Senin'] = $data['Senin']  == '' ? NULL : $data['Senin'];
        $dt['SeninTutup'] = $data['SeninTutup']  == '' ? NULL : $data['SeninTutup'];
        $dt['Selasa'] = $data['Selasa']  == '' ? NULL : $data['Selasa'];
        $dt['SelasaTutup'] = $data['SelasaTutup']  == '' ? NULL : $data['SelasaTutup'];
        $dt['Rabu'] = $data['Rabu']  == '' ? NULL : $data['Rabu'];
        $dt['RabuTutup'] = $data['RabuTutup']  == '' ? NULL : $data['RabuTutup'];
        $dt['Kamis'] = $data['Kamis']  == '' ? NULL : $data['Kamis'];
        $dt['KamisTutup'] = $data['KamisTutup']  == '' ? NULL : $data['KamisTutup'];
        $dt['Jumat'] = $data['Jumat']  == '' ? NULL : $data['Jumat'];
        $dt['JumatTutup'] = $data['JumatTutup']  == '' ? NULL : $data['JumatTutup'];
        $dt['Sabtu'] = $data['Sabtu']  == '' ? NULL : $data['Sabtu'];
        $dt['SabtuTutup'] = $data['SabtuTutup']  == '' ? NULL : $data['SabtuTutup'];
        $dt['Minggu'] = $data['Minggu']  == '' ? NULL : $data['Minggu'];
        $dt['MingguTutup'] = $data['MingguTutup']  == '' ? NULL : $data['MingguTutup'];
        $dt['JamBukaPendaftaran'] = $data['JamBukaPendaftaran']  == '' ? NULL : $data['JamBukaPendaftaran'];
        $dt['JamTutupPendaftaran'] = $data['JamTutupPendaftaran']  == '' ? NULL : $data['JamTutupPendaftaran'];
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
        $dt['Senin'] = $data['Senin']  == '' ? NULL : $data['Senin'];
        $dt['SeninTutup'] = $data['SeninTutup']  == '' ? NULL : $data['SeninTutup'];
        $dt['Selasa'] = $data['Selasa']  == '' ? NULL : $data['Selasa'];
        $dt['SelasaTutup'] = $data['SelasaTutup']  == '' ? NULL : $data['SelasaTutup'];
        $dt['Rabu'] = $data['Rabu']  == '' ? NULL : $data['Rabu'];
        $dt['RabuTutup'] = $data['RabuTutup']  == '' ? NULL : $data['RabuTutup'];
        $dt['Kamis'] = $data['Kamis']  == '' ? NULL : $data['Kamis'];
        $dt['KamisTutup'] = $data['KamisTutup']  == '' ? NULL : $data['KamisTutup'];
        $dt['Jumat'] = $data['Jumat']  == '' ? NULL : $data['Jumat'];
        $dt['JumatTutup'] = $data['JumatTutup']  == '' ? NULL : $data['JumatTutup'];
        $dt['Sabtu'] = $data['Sabtu']  == '' ? NULL : $data['Sabtu'];
        $dt['SabtuTutup'] = $data['SabtuTutup']  == '' ? NULL : $data['SabtuTutup'];
        $dt['Minggu'] = $data['Minggu']  == '' ? NULL : $data['Minggu'];
        $dt['MingguTutup'] = $data['MingguTutup']  == '' ? NULL : $data['MingguTutup'];
        $dt['JamBukaPendaftaran'] = $data['JamBukaPendaftaran']  == '' ? NULL : $data['JamBukaPendaftaran'];
        $dt['JamTutupPendaftaran'] = $data['JamTutupPendaftaran']  == '' ? NULL : $data['JamTutupPendaftaran'];
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
        $poli = $this->delete('poli');
        $waktu = $this->delete('waktu');
        $dokter = $this->delete('dokter');
        $this->db->where('KodeKlinik', $poli);
        $this->db->where('JenisWaktu', $waktu);
        $this->db->where('KodeDokter', $dokter);
        $delete = $this->db->delete('RegJadwalKlinik');
        if ($delete) {
            $this->response(array('status' => true), 200);
        } else {
            $this->response(array('status' => false, 502));
        }
    }
}
