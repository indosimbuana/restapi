<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Mstgoldarah extends RestController
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

        $this->load->model('mgoldarah');
        if ($id === null) {
            $a = $this->mgoldarah->getGolDarah();
            if ($a) {
                $n = 0;
                foreach ($a as $dt) {
                    $data[$n]['kdgoldarah'] = $dt['KodeGolDarah'];
                    $data[$n]['nmgoldarah'] = trim($dt['NamaGolDarah']);
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
                    'message' => 'No gol darah were found'
                ], 404);
            }
        } else {
            $da = $this->mgoldarah->getGolDarahById($id);
            if ($da) {
                $data['kdgoldarah'] = $da->KodeGolDarah;
                $data['nmgoldarah'] = trim($da->NamaGolDarah);
                $this->response([
                    'status' => true,
                    'message' => 'Data found',
                    'data' => $data
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No gol darah were found'
                ], 404);
            }
        }
    }
}
