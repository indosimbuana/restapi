<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Mstpekerjaan extends RestController
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

        $this->load->model('mpekerjaan');
        if ($id === null) {
            $a = $this->mpekerjaan->getPekerjaan();
            if ($a) {
                $n = 0;
                foreach ($a as $dt) {
                    $data[$n]['kdpekerjaan'] = $dt['KodePekerjaan'];
                    $data[$n]['nmpekerjaan'] = trim($dt['NamaPekerjaan']);
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
                    'message' => 'No pekerjaan were found'
                ], 404);
            }
        } else {
            $da = $this->mpekerjaan->getPekerjaanById($id);
            if ($da) {
                $data['kdpekerjaan'] = $da->KodePekerjaan;
                $data['nmpekerjaan'] = trim($da->NamaPekerjaan);
                $this->response([
                    'status' => true,
                    'message' => 'Data found',
                    'data' => $data
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No pekerjaan were found'
                ], 404);
            }
        }
    }
}
