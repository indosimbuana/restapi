<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Kritiksaran extends RestController
{

    function __construct()
    {
        parent::__construct();
    }

    public function index_get()
    {
        $id = $this->get('id');
        $bag = $this->get('bag');

        $this->load->model('mkritiksaran');
        if ($id === null && $bag === null) {
            $a = $this->mkritiksaran->getKritSar();
            if ($a) {
                $n = 0;
                foreach ($a as $dt) {
                    $data[$n]['idkritsar'] = $dt['idKritSar'];
                    $data[$n]['idakun'] = trim($dt['idAkun']);
                    $data[$n]['kritik'] = trim($dt['kritik']);
                    $data[$n]['saran'] = trim($dt['saran']);
                    $data[$n]['jawaban'] = trim($dt['jawaban']);
                    $data[$n]['kodebagian'] = trim($dt['kodeBagian']);
                    $data[$n]['status'] = trim($dt['status']);
                    $data[$n]['tgltanya'] = trim($dt['tglTanya']);
                    $data[$n]['penjawab'] = trim($dt['penjawab']);
                    $data[$n]['tgljawab'] = trim($dt['tglJawab']);
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
        } else if ($id === null && $bag != null) {
            $a = $this->mkritiksaran->getKritSarByBag($bag);
            if ($a) {
                $n = 0;
                foreach ($a as $dt) {
                    $data[$n]['idkritsar'] = $dt['idKritSar'];
                    $data[$n]['idakun'] = trim($dt['idAkun']);
                    $data[$n]['kritik'] = trim($dt['kritik']);
                    $data[$n]['saran'] = trim($dt['saran']);
                    $data[$n]['jawaban'] = trim($dt['jawaban']);
                    $data[$n]['kodebagian'] = trim($dt['kodeBagian']);
                    $data[$n]['status'] = trim($dt['status']);
                    $data[$n]['tgltanya'] = trim($dt['tglTanya']);
                    $data[$n]['penjawab'] = trim($dt['penjawab']);
                    $data[$n]['tgljawab'] = trim($dt['tglJawab']);
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
            $da = $this->mkritiksaran->cekKritSar($id);
            if ($da) {
                $data['idkritsar'] = $da->idKritSar;
                $data['idakun'] = trim($da->idAkun);
                $data['kritik'] = trim($da->kritik);
                $data['saran'] = trim($da->saran);
                $data['jawaban'] = trim($da->jawaban);
                $data['kodebagian'] = trim($da->kodeBagian);
                $data['status'] = trim($da->status);
                $data['tgltanya'] = trim($da->tglTanya);
                $data['penjawab'] = trim($da->penjawab);
                $data['tgljawab'] = trim($da->tglJawab);
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

        $date = date('yymd');

        $dt = array();
        $dt['idKritSar'] = $date . rand(1000, 10000);
        $dt['idAkun'] = $data['idakun'];
        $dt['kritik'] = $data['kritik'];
        $dt['saran'] = $data['saran'];
        $dt['kodeBagian'] = $data['bagian'];

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
