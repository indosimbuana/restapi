<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Mstlevelakun extends RestController
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

        $this->load->model('mlevelakun');
        if ($id === null) {
            $b = $this->mlevelakun->getLevel();
            if ($b) {
                $n = 0;
                foreach ($b as $dt) {
                    $data[$n]['idlevel'] = $dt['IDLevel'];
                    $data[$n]['nmlevel'] = $dt['NamaLevel'];
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
                    'message' => 'No data were found'
                ], 404);
            }
        } else {
            $b = $this->mlevelakun->getLevelById($id);
            if ($b) {
                $data['idlevel'] = $b->IDLevel;
                $data['nmlevel'] = $b->NamaLevel;
                $this->response([
                    'status' => true,
                    'message' => 'Data found',
                    'data' => $data
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No data were found'
                ], 404);
            }
        }
    }
}
