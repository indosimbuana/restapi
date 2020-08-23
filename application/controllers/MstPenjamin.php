<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Mstpenjamin extends RestController
{

    function __construct()
    {
        parent::__construct();
    }

    public function index_get()
    {
        $id = $this->get('id');

        $this->load->model('mpenjamin');
        if ($id === null) {
            $a = $this->mpenjamin->getPenjamin();
            if ($a) {
                $n = 0;
                foreach ($a as $dt) {
                    $data[$n]['kdpenjamin'] = $dt['idPenjamin'];
                    $data[$n]['nmpenjamin'] = trim($dt['Nama']);
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
                    'message' => 'No penjamin were found'
                ], 404);
            }
        } else {
            $da = $this->mpenjamin->getPenjaminById($id);
            if ($da) {
                $data['kdpenjamin'] = $da->idPenjamin;
                $data['nmpenjamin'] = trim($da->Nama);
                $this->response([
                    'status' => true,
                    'message' => 'Data found',
                    'data' => $data
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No penjamin were found'
                ], 404);
            }
        }
    }
}
