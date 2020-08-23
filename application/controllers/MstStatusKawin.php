<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Mststatuskawin extends RestController
{

    function __construct()
    {
        parent::__construct();
    }

    public function index_get()
    {
        $id = $this->get('id');

        $this->load->model('mstskwn');
        if ($id === null) {
            $a = $this->mstskwn->getStsKwn();
            if ($a) {
                $n = 0;
                foreach ($a as $dt) {
                    $data[$n]['kdsts'] = $dt['KodeStatus'];
                    $data[$n]['nmsts'] = trim($dt['NamaStatus']);
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
                    'message' => 'No status kawin were found'
                ], 404);
            }
        } else {
            $da = $this->mstskwn->getStsKwnById($id);
            if ($da) {
                $data['kdsts'] = $da->KodeStatus;
                $data['nmsts'] = trim($da->NamaStatus);
                $this->response([
                    'status' => true,
                    'message' => 'Data found',
                    'data' => $data
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No status kawin were found'
                ], 404);
            }
        }
    }
}
