<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Mstagama extends RestController
{

    function __construct()
    {
        parent::__construct();
    }

    public function index_get()
    {
        $id = $this->get('id');

        $this->load->model('magama');
        if ($id === null) {
            $a = $this->magama->getAgama();
            if ($a) {
                $n = 0;
                foreach ($a as $dt) {
                    $data[$n]['kdagama'] = $dt['KodeAgama'];
                    $data[$n]['nmagama'] = trim($dt['NamaAgama']);
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
                    'message' => 'No agama were found'
                ], 404);
            }
        } else {
            $da = $this->magama->getAgamaById($id);
            if ($da) {
                $data['kdagama'] = $da->KodeAgama;
                $data['nmagama'] = trim($da->NamaAgama);
                $this->response([
                    'status' => true,
                    'message' => 'Data found',
                    'data' => $data
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No agama were found'
                ], 404);
            }
        }
    }
}
