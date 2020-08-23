<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Mstsuku extends RestController
{

    function __construct()
    {
        parent::__construct();
    }

    public function index_get()
    {
        $id = $this->get('id');

        $this->load->model('msuku');
        if ($id === null) {
            $a = $this->msuku->getSuku();
            if ($a) {
                $n = 0;
                foreach ($a as $dt) {
                    $data[$n]['kdsuku'] = $dt['KodeSuku'];
                    $data[$n]['nmsuku'] = trim($dt['NamaSuku']);
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
                    'message' => 'No suku were found'
                ], 404);
            }
        } else {
            $da = $this->msuku->getSukuById($id);
            if ($da) {
                $data['kdsuku'] = $da->KodeSuku;
                $data['nmsuku'] = trim($da->NamaSuku);
                $this->response([
                    'status' => true,
                    'message' => 'Data found',
                    'data' => $data
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No suku were found'
                ], 404);
            }
        }
    }
}
