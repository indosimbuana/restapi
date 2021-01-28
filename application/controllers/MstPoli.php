<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Mstpoli extends RestController
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

        $this->load->model('mpoli');
        if ($id === null) {
            $a = $this->mpoli->getPoli();
            if ($a) {
                $n = 0;
                foreach ($a as $dt) {
                    $data[$n]['kdpoli'] = $dt['KodeBagian'];
                    $data[$n]['nmpoli'] = trim($dt['NamaBagian']);
                    $data[$n]['kdantrian'] = $dt['KodeAntrian'];
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
            $da = $this->mpoli->getPoliById($id);
            if ($da) {
                $data['kdpoli'] = $da->KodeBagian;
                $data['nmpoli'] = trim($da->NamaBagian);
                $data['kdantrian'] = $da->KodeAntrian;
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
