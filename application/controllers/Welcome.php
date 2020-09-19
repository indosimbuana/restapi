<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Welcome extends RestController
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

	public function index()
	{
		$this->load->view('welcome_message');
	}
}
