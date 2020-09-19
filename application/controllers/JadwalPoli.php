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
                    $data[$n]['senin'] = $p['Senin'] == NULL ? "Libur" : date_format(date_create($p['Senin']), "H:s");
                    $data[$n]['selasa'] = $p['Selasa'] == NULL ? "Libur" : date_format(date_create($p['Selasa']), "H:s");
                    $data[$n]['rabu'] = $p['Rabu'] == NULL ? "Libur" : date_format(date_create($p['Rabu']), "H:s");
                    $data[$n]['kamis'] = $p['Kamis'] == NULL ? "Libur" : date_format(date_create($p['Kamis']), "H:s");
                    $data[$n]['jumat'] = $p['Jumat'] == NULL ? "Libur" : date_format(date_create($p['Jumat']), "H:s");
                    $data[$n]['sabtu'] = $p['Sabtu'] == NULL ? "Libur" : date_format(date_create($p['Sabtu']), "H:s");
                    $data[$n]['minggu'] = $p['Minggu'] == NULL ? "Libur" : date_format(date_create($p['Minggu']), "H:s");
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
                    $data[$n]['senin'] = $p['Senin'] == NULL ? "Libur" : date_format(date_create($p['Senin']), "H:s");
                    $data[$n]['selasa'] = $p['Selasa'] == NULL ? "Libur" : date_format(date_create($p['Selasa']), "H:s");
                    $data[$n]['rabu'] = $p['Rabu'] == NULL ? "Libur" : date_format(date_create($p['Rabu']), "H:s");
                    $data[$n]['kamis'] = $p['Kamis'] == NULL ? "Libur" : date_format(date_create($p['Kamis']), "H:s");
                    $data[$n]['jumat'] = $p['Jumat'] == NULL ? "Libur" : date_format(date_create($p['Jumat']), "H:s");
                    $data[$n]['sabtu'] = $p['Sabtu'] == NULL ? "Libur" : date_format(date_create($p['Sabtu']), "H:s");
                    $data[$n]['minggu'] = $p['Minggu'] == NULL ? "Libur" : date_format(date_create($p['Minggu']), "H:s");
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
}
