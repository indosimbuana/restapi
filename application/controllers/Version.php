<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Version extends RestController
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
        $this->load->model('mversion');

        $vr = $this->mversion->getVersion();
        if ($vr) {
            $this->response([
                'status' => true,
                'message' => 'Data found',
                'version' => $vr->AndroidVersion
            ], 200);
        } else {
            $this->response([
                'status' => false,
                'message' => 'No agama were found'
            ], 404);
        }
    }

    function index_put()
    {
        $d = file_get_contents('php://input');
        $data = json_decode($d, true);

        $dt = array();
        $dt['AndroidVersion'] = $data['version'] == '' ? NULL : $data['version'];

        $this->load->model('mversion');

        if ($dt['AndroidVersion'] == NULL) {
            $this->response([
                'status' => false,
                'message' => 'Gagal update Version'
            ], 400);
        } else {
            $cek = $this->mversion->getVersion();
            if ($cek) {
                if ($this->mversion->updateVersion($dt)) {
                    $this->response([
                        'status' => true,
                        'message' => 'Version berhasil diupdate'
                    ], 200);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Gagal update Version'
                    ], 400);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Gagal update Version, data tidak ditemukan'
                ], 400);
            }
        }
    }
}
