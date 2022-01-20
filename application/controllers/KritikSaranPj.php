<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Kritiksaranpj extends RestController
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
        $pj = $this->get('pj');
        $tgl = $this->get('tgl');
        $awal = $this->get('awal');
        $akhir = $this->get('akhir');

        $this->load->model('mkritiksaranpj');
        if ($tgl === null) {
            if ($awal === null && $akhir === null) {
                $a = $this->mkritiksaranpj->getKritSar($pj);
                if ($a) {
                    $n = 0;
                    foreach ($a as $dt) {
                        $data[$n]['idkritsar'] = $dt['idKritSar'];
                        $data[$n]['idakun'] = trim($dt['idAkun']);
                        $data[$n]['nama'] = trim($dt['nama']);
                        $data[$n]['alamat'] = trim($dt['alamat']);
                        $data[$n]['kodebagian'] = trim($dt['kodeBagian']);
                        $data[$n]['namabagian'] = trim($dt['NamaBagian']);
                        $data[$n]['telp'] = trim($dt['telp']);
                        $data[$n]['email'] = trim($dt['email']);
                        $data[$n]['kritiksaran'] = trim($dt['kritikSaran']);
                        $data[$n]['tgltanya'] = $dt['tglTanya'] == NULL ? '' : date_format(date_create($dt['tglTanya']), "d-m-Y H:i");
                        $data[$n]['jawaban'] = trim($dt['jawaban']);
                        $data[$n]['penjawab'] = trim($dt['penjawab']);
                        $data[$n]['tgljawab'] = $dt['tglJawab'] == NULL ? '' : date_format(date_create($dt['tglJawab']), "d-m-Y H:i");
                        $data[$n]['status'] = trim($dt['status']);
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
                        'message' => 'No kritik saran were found'
                    ], 404);
                }
            } else {
                $a = $this->mkritiksaranpj->getKritSarByRangeTgl($pj, $awal, $akhir);
                if ($a) {
                    $n = 0;
                    foreach ($a as $dt) {
                        $data[$n]['idkritsar'] = $dt['idKritSar'];
                        $data[$n]['idakun'] = trim($dt['idAkun']);
                        $data[$n]['nama'] = trim($dt['nama']);
                        $data[$n]['alamat'] = trim($dt['alamat']);
                        $data[$n]['kodebagian'] = trim($dt['kodeBagian']);
                        $data[$n]['namabagian'] = trim($dt['NamaBagian']);
                        $data[$n]['telp'] = trim($dt['telp']);
                        $data[$n]['email'] = trim($dt['email']);
                        $data[$n]['kritiksaran'] = trim($dt['kritikSaran']);
                        $data[$n]['tgltanya'] = $dt['tglTanya'] == NULL ? '' : date_format(date_create($dt['tglTanya']), "d-m-Y H:i");
                        $data[$n]['jawaban'] = trim($dt['jawaban']);
                        $data[$n]['penjawab'] = trim($dt['penjawab']);
                        $data[$n]['tgljawab'] = $dt['tglJawab'] == NULL ? '' : date_format(date_create($dt['tglJawab']), "d-m-Y H:i");
                        $data[$n]['status'] = trim($dt['status']);
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
                        'message' => 'No kritik saran were found'
                    ], 404);
                }
            }
        } else {
            $a = $this->mkritiksaranpj->getKritSarByTgl($pj, $tgl);
            if ($a) {
                $n = 0;
                foreach ($a as $dt) {
                    $data[$n]['idkritsar'] = $dt['idKritSar'];
                    $data[$n]['idakun'] = trim($dt['idAkun']);
                    $data[$n]['nama'] = trim($dt['nama']);
                    $data[$n]['alamat'] = trim($dt['alamat']);
                    $data[$n]['kodebagian'] = trim($dt['kodeBagian']);
                    $data[$n]['namabagian'] = trim($dt['NamaBagian']);
                    $data[$n]['telp'] = trim($dt['telp']);
                    $data[$n]['email'] = trim($dt['email']);
                    $data[$n]['kritiksaran'] = trim($dt['kritikSaran']);
                    $data[$n]['tgltanya'] = $dt['tglTanya'] == NULL ? '' : date_format(date_create($dt['tglTanya']), "d-m-Y H:i");
                    $data[$n]['jawaban'] = trim($dt['jawaban']);
                    $data[$n]['penjawab'] = trim($dt['penjawab']);
                    $data[$n]['tgljawab'] = $dt['tglJawab'] == NULL ? '' : date_format(date_create($dt['tglJawab']), "d-m-Y H:i");
                    $data[$n]['status'] = trim($dt['status']);
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
                    'message' => 'No kritik saran were found'
                ], 404);
            }
        }
    }
}
