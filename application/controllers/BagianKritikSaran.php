<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class BagianKritikSaran extends RestController
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
        $id = $this->get('user');

        $this->load->model('mbagiankritiksaran');
        if ($id === null) {
            $a = $this->mbagiankritiksaran->getBagian();
            if ($a) {
                $n = 0;
                foreach ($a as $dt) {
                    $data[$n]['kdbag'] = $dt['KodeBagian'];
                    $data[$n]['nmbag'] = trim($dt['NamaBagian']);
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
                    'message' => 'No bagian were found'
                ], 404);
            }
        } else {
            $da = $this->mbagiankritiksaran->getBagianByUser($id);
            if ($da) {
                $n = 0;
                foreach ($da as $dt) {
                    $data[$n]['kdbag'] = $dt['KodeBagian'];
                    $data[$n]['nmbag'] = trim($dt['NamaBagian']);
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
                    'message' => 'No bagian were found'
                ], 404);
            }
        }
    }
}
