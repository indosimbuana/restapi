<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Rating extends RestController
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

        $this->load->model('mrating');

        $cek = $this->mrating->cekPasien($akun);
        if ($cek) {
            $a = $this->mrating->getRating();
            if ($a) {
                $n = 0;
                foreach ($a as $dt) {
                    $data[$n]['kdtanya'] = $dt['KodeTanya'];
                    $data[$n]['isitanya'] = trim($dt['IsiTanya']);
                    $data[$n]['objek'] = trim($dt['ObjekTanya']);
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
                    'message' => 'No question were found'
                ], 404);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'No question were found'
            ], 404);
        }
    }

    public function index_post()
    {
        $d = file_get_contents('php://input');
        $data = json_decode($d, true);

        $dt = array();
        $dt['KodeBooking'] = $data['KodeBooking'];
        $dt['KodeTanya'] = $data['KodeTanya'];
        $dt['NilaiRating'] = $data['NilaiRating'];
        $dt['UserRating'] = $data['UserRating'];

        $this->load->model('mrating');

        if ($dt['KodeBooking'] == '' && $dt['KodeBooking'] == '' && $dt['KodeBooking'] == '' && $dt['KodeBooking'] == '') {
            $this->response([
                'status' => false,
                'message' => 'Gagal kirim rating, lengkapi data!'
            ], 400);
        } else {
            if ($this->mrating->kirimRating($dt)) {
                $this->response([
                    'status' => true,
                    'message' => 'Berhasil kirim rating'
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Gagal kirim rating'
                ], 400);
            }
        }
    }
}
