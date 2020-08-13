<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class RegAkun extends RestController
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('mregakun');
    }

    function index_get()
    {
        $id = $this->get('id');

        if ($id === null) {
            $b = $this->mbagian->getBagian();
            if ($b) {
                $this->response([
                    'status' => true,
                    'message' => 'Data found',
                    'data' => $b
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No users were found'
                ], 404);
            }
        } else {
            $b = $this->mbagian->getBagianById($id);
            if ($b) {
                $this->response([
                    'status' => true,
                    'message' => 'Data found',
                    'data' => $b
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No users were found'
                ], 404);
            }
        }
    }

    public function index_post()
    {
        $this->load->library('enkripdekrip');

        $d = file_get_contents('php://input');
        $data = json_decode($d, true);

        $nama = $data['nama'];
        $email = $data['email'];
        $telp = $data['telp'];
        $password = $this->enkripdekrip->proses($data['password']);

        // $nama = $this->post('nama');
        // $email = $this->post('email');
        // $telp = $this->post('telp');
        // $password = $this->enkripdekrip->proses($this->post('password'));

        $this->response([
            'nama' => $nama,
            'email' => $email,
            'telp' => $telp,
            'password' => $password
        ], 200);
    }
}
