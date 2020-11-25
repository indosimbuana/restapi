<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Infott extends RestController
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
        $kls = $this->get('kls');

        $this->load->model('minfott');
        if ($kls === null) {
            $i = $this->minfott->getInfoTT();
            if ($i) {
                $data = array();
                $n = 0;
                foreach ($i as $ik) {
                    $data[$n]['Kelas'] = $ik['Kelas'];
                    $data[$n]['NamaKelas'] = $ik['NamaKelas'];
                    $data[$n]['JumlahTT'] = $ik['JumlahTT'];
                    $data[$n]['JumlahTTKosong'] = $ik['JumlahTTKosong'];

                    $f = $this->minfott->fotoKelas($ik['Kelas']);
                    $df = array();
                    $nf = 0;
                    foreach ($f as $ft) {
                        $df[$nf]['idfoto'] = $ft['idfoto'];
                        $df[$nf]['kelas'] = $ft['kelas'];
                        $df[$nf]['label'] = $ft['label'];
                        $df[$nf]['url'] = $ft['url'] ? base_url() . $ft['url'] : '';
                        $df[$nf]['tglupload'] = $ft['tglupload'];
                        $nf++;
                    }

                    $data[$n]['Foto'] = $df;

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
                    'message' => 'No info were found'
                ], 404);
            }
        } else {
            $i = $this->minfott->detailInfoTT($kls);
            if ($i) {
                $this->response([
                    'status' => true,
                    'message' => 'Data found',
                    'data' => $i
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No info were found'
                ], 404);
            }
        }
    }
}
