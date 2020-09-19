<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Pilihtanggal extends RestController
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
        $date = new DateTime();
        $th = $date->format('Y');

        if ($th === null || $th === null) {
            $this->response([
                'status' => false,
                'message' => 'Tahun kosong'
            ], 404);
        } else {
            $this->load->model('mpilihtanggal');
            $libur = $this->mpilihtanggal->getHariLiburByTahun($th);

            $lb = array();
            foreach ($libur as $l) {
                $lb[] = $l['TglLibur'];
            }

            $i = 0;
            $weekend = array('Sun');
            $nextDates = array();
            while ($i < 7) {
                $date->add(new DateInterval('P1D'));
                if (in_array($date->format('Y-m-d'), $lb)) continue;
                if (in_array($date->format('D'), $weekend)) continue;
                $nextDates[] = $date->format('Y-m-d');
                $i++;
            }

            $av = array();
            foreach ($nextDates as $nd) {
                $av[] = $nd;
            }

            $this->response([
                'status' => true,
                'message' => 'Berhasil generate tanggal',
                'tahun' => $th,
                'harilibur' => $lb,
                'data' => $av
            ], 200);
        }
    }
}
