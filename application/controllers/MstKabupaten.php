<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Mstkabupaten extends RestController
{

    function __construct()
    {
        parent::__construct();
    }

    public function index_get()
    {
        $prov = $this->get('prov');

        $this->load->model('mkabupaten');
        if ($prov === null) {
            $k = $this->mkabupaten->getKabupaten();
            if ($k) {
                $n = 0;
                foreach ($k as $dt) {
                    $data[$n]['kdprov'] = $dt['KODEPROV'];
                    $data[$n]['kdkab'] = $dt['KODEKOTA'];
                    $data[$n]['nmkab'] = $dt['NAMAKOTA'];
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
                    'message' => 'No kabupaten were found'
                ], 404);
            }
        } else {
            $dk = $this->mkabupaten->getKabupatenByProv($prov);
            if ($dk) {
                $n = 0;
                foreach ($dk as $dt) {
                    $data[$n]['kdkab'] = $dt['KODEKOTA'];
                    $data[$n]['nmkab'] = $dt['NAMAKOTA'];
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
                    'message' => 'No kabupaten were found'
                ], 404);
            }
        }
    }
}
