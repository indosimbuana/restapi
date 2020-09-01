<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Mstbagianks extends RestController
{

    function __construct()
    {
        parent::__construct();
    }

    public function index_get()
    {
        $id = $this->get('id');

        $this->load->model('mbagianks');
        if ($id === null) {
            $a = $this->mbagianks->getBagianKS();
            if ($a) {
                $n = 0;
                foreach ($a as $dt) {
                    $data[$n]['kdbagianks'] = $dt['KodeBagian'];
                    $data[$n]['nmbagianks'] = trim($dt['NamaBagian']);
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
            $da = $this->mbagianks->getBagianKSById($id);
            if ($da) {
                $data['kdbagianks'] = $da->KodeBagian;
                $data['nmbagianks'] = trim($da->NamaBagian);
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
