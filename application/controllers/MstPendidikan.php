<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Mstpendidikan extends RestController
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

        $this->load->model('mpendidikan');
        if ($id === null) {
            $a = $this->mpendidikan->getPendidikan();
            if ($a) {
                $n = 0;
                foreach ($a as $dt) {
                    $data[$n]['kdpendidikan'] = $dt['KodeLulusan'];
                    $data[$n]['nmpendidikan'] = trim($dt['NamaLulusan']);
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
                    'message' => 'No pendidikan were found'
                ], 404);
            }
        } else {
            $da = $this->mpendidikan->getPendidikanById($id);
            if ($da) {
                $data['kdpendidikan'] = $da->KodeLulusan;
                $data['nmpendidikan'] = trim($da->NamaLulusan);
                $this->response([
                    'status' => true,
                    'message' => 'Data found',
                    'data' => $data
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No pendidikan were found'
                ], 404);
            }
        }
    }
}
