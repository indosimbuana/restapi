<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Infott extends RestController
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
        $kls = $this->get('kls');

        $this->load->model('minfott');
        if ($kls === null) {
            $i = $this->minfott->getInfoTT();
            if ($i) {
                $this->response([
                    'status' => true,
                    'message' => 'Data found',
                    'data' => $i
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No info were found'
                ], 404);
            }
        } else {
            $i = $this->minfott->detailInfoTT($kls);
            if ($i) {
                $this->response([
                    'status' => true,
                    'message' => 'Data found',
                    'data' => $i
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No info were found'
                ], 404);
            }
        }
    }
}
