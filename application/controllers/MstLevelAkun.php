<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Mstlevelakun extends RestController
{

    function __construct()
    {
        parent::__construct();
    }

    public function index_get()
    {
        $id = $this->get('id');

        $this->load->model('mlevelakun');
        if ($id === null) {
            $b = $this->mlevelakun->getLevel();
            if ($b) {
                $this->response([
                    'status' => true,
                    'message' => 'Data found',
                    'data' => $b
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
                $this->response([
                    'status' => true,
                    'message' => 'Data found',
                    'data' => $b
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
