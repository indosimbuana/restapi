<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Mstdokter extends RestController
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

        $this->load->model('mdokter');
        if ($id === null) {
            $a = $this->mdokter->getDokter();
            if ($a) {
                $n = 0;
                foreach ($a as $dt) {
                    $data[$n]['kddokter'] = $dt['KodeDokter'];
                    $data[$n]['nmdokter'] = trim($dt['NamaDokter']);
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
                    'message' => 'No dokter were found'
                ], 404);
            }
        } else {
            $da = $this->mdokter->getDokterById($id);
            if ($da) {
                $data['kddokter'] = $da->KodeDokter;
                $data['nmdokter'] = trim($da->NamaDokter);
                $this->response([
                    'status' => true,
                    'message' => 'Data found',
                    'data' => $data
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No dokter were found'
                ], 404);
            }
        }
    }
}
