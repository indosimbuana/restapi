<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Mstbahasa extends RestController
{

    function __construct()
    {
        parent::__construct();
    }

    public function index_get()
    {
        $id = $this->get('id');

        $this->load->model('mbahasa');
        if ($id === null) {
            $a = $this->mbahasa->getBahasa();
            if ($a) {
                $n = 0;
                foreach ($a as $dt) {
                    $data[$n]['kdbahasa'] = $dt['KodeBahasa'];
                    $data[$n]['nmbahasa'] = trim($dt['NamaBahasa']);
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
                    'message' => 'No bahasa were found'
                ], 404);
            }
        } else {
            $da = $this->mbahasa->getBahasaById($id);
            if ($da) {
                $data['kdbahasa'] = $da->KodeBahasa;
                $data['nmbahasa'] = trim($da->NamaBahasa);
                $this->response([
                    'status' => true,
                    'message' => 'Data found',
                    'data' => $data
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No bahasa were found'
                ], 404);
            }
        }
    }
}
