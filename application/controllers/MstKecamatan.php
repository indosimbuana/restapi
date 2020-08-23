<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Mstkecamatan extends RestController
{

    function __construct()
    {
        parent::__construct();
    }

    public function index_get()
    {
        $prov = $this->get('prov');
        $kab = $this->get('kab');

        $this->load->model('mkecamatan');
        if ($prov === null && $kab === null) {
            $k = $this->mkecamatan->getKecamatan();
            if ($k) {
                $n = 0;
                foreach ($k as $dt) {
                    $data[$n]['kdprov'] = $dt['KODEPROV'];
                    $data[$n]['kdkab'] = $dt['KODEKOTA'];
                    $data[$n]['kdkec'] = $dt['KODEKEC'];
                    $data[$n]['nmkec'] = $dt['NAMAKEC'];
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
                    'message' => 'No kecamatan were found'
                ], 404);
            }
        } else {
            $dk = $this->mkecamatan->getKecamatanByKab($prov, $kab);
            if ($dk) {
                $n = 0;
                foreach ($dk as $dt) {
                    $data[$n]['kdkec'] = $dt['KODEKEC'];
                    $data[$n]['nmkec'] = $dt['NAMAKEC'];
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
                    'message' => 'No kecamatan were found'
                ], 404);
            }
        }
    }
}
