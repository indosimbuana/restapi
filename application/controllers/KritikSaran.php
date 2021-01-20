<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Kritiksaran extends RestController
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
        $akun = $this->get('akun');
        $id = $this->get('id');
        $bag = $this->get('bag');
        $tgl = $this->get('tgl');

        $this->load->model('mkritiksaran');
        if ($akun === null) {
            if ($id === null && $bag === null) {
                if ($tgl === null) {
                    $a = $this->mkritiksaran->getKritSar();
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
                    $a = $this->mkritiksaran->getKritSarByTgl($tgl);
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
            } else if ($id === null && $bag != null) {
                if ($tgl === null) {
                    $a = $this->mkritiksaran->getKritSarByBag($bag);
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
                    $a = $this->mkritiksaran->getKritSarByBagByTgl($bag, $tgl);
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
                $da = $this->mkritiksaran->cekKritSar($id);
                if ($da) {
                    $data['idkritsar'] = $da->idKritSar;
                    $data['idakun'] = trim($da->idAkun);
                    $data['nama'] = trim($da->nama);
                    $data['alamat'] = trim($da->alamat);
                    $data['kodebagian'] = trim($da->kodeBagian);
                    $data['namabagian'] = trim($da->NamaBagian);
                    $data['telp'] = trim($da->telp);
                    $data['email'] = trim($da->email);
                    $data['kritiksaran'] = trim($da->kritikSaran);
                    $data['tgltanya'] = $da->tglTanya == NULL ? '' : date_format(date_create($da->tglTanya), "d-m-Y H:i");
                    $data['jawaban'] = trim($da->jawaban);
                    $data['penjawab'] = trim($da->penjawab);
                    $data['tgljawab'] = $da->tglJawab == NULL ? '' : date_format(date_create($da->tglJawab), "d-m-Y H:i");
                    $data['status'] = trim($da->status);
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
            $a = $this->mkritiksaran->getKritSarByAkun($akun);
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

    public function index_post()
    {
        $d = file_get_contents('php://input');
        $data = json_decode($d, true);

        $date = date('Ymd');

        $dt = array();
        $dt['idKritSar'] = $date . rand(1000, 10000);
        $dt['idAkun'] = $data['idakun'];
        $dt['nama'] = $data['nama'];
        $dt['alamat'] = $data['alamat'];
        $dt['kodeBagian'] = $data['bagian'];
        $dt['telp'] = $data['telp'];
        $dt['email'] = $data['email'];
        $dt['kritikSaran'] = $data['kritiksaran'];

        $this->load->model('mkritiksaran');

        if ($this->mkritiksaran->kirimKritSar($dt)) {
            $this->response([
                'status' => true,
                'message' => 'Berhasil kirim kritik dan saran'
            ], 200);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Gagal kirim kritik dan saran'
            ], 400);
        }
    }

    public function jawab_post()
    {
        $d = file_get_contents('php://input');
        $data = json_decode($d, true);

        $dt = array();
        $dt['idKritSar'] = $data['idkritsar'];
        $dt['jawaban'] = $data['jawaban'];
        $dt['status'] = 1;
        $dt['penjawab'] = $data['penjawab'];
        $dt['tglJawab'] = date("yy-m-d H:i:s");

        $this->load->model('mkritiksaran');

        if ($this->mkritiksaran->cekKritSar($dt['idKritSar'])) {
            if ($this->mkritiksaran->jawabKritSar($dt)) {
                $this->response([
                    'status' => true,
                    'message' => 'Berhasil jawab kritik dan saran'
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Gagal jawab kritik dan saran'
                ], 400);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Kritik saran tidak ditemukan'
            ], 404);
        }
    }
}
