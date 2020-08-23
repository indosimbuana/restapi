<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Mstbagian extends RestController
{

    function __construct()
    {
        parent::__construct();
    }

    public function index_get()
    {
        $id = $this->get('id');

        $this->load->model('mbagian');
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
}
