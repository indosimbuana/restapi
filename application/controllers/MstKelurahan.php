<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Mstkelurahan extends RestController
{

    function __construct()
    {
        parent::__construct();
    }

    public function index_get()
    {
        $id = $this->get('id');
        $kec = $this->get('kec');
    }
}
