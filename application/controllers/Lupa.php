<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Lupa extends RestController
{

    function __construct()
    {
        parent::__construct();
    }

    public function index_post()
    {
        $this->load->library('mail');
        $d = file_get_contents('php://input');
        $data = json_decode($d, true);

        $dt = array();
        $dt['user'] = $data['user'];

        $this->load->model('mlupa');
        $cekakun = $this->mlupa->cekakun($dt);

        if (isset($data['type'])) {
            if ($cekakun) {
                if ($data['type'] == "password") {
                    $kd = array();
                    $kd['kode'] = date("d") . rand(999, 10000);
                    $kd['telp'] = $cekakun->NoTelpon;

                    $body['judul'] = "Lupa Password";
                    $body['sapaan'] = "Halo kak, nih kode lupa passwordnya: ";
                    $body['isi'] = $kd['kode'];

                    if ($this->mlupa->simpanKode($kd)) {
                        if ($this->mail->kirim($cekakun->Email, $body['judul'], $body) == true) {
                            $this->response([
                                'status' => true,
                                'message' => 'Kode konfirmasi lupa password telah dikirim ke email yang terdaftar ' . $cekakun->Email,
                                'code' => $kd['kode']
                            ], 200);
                        } else {
                            $this->response([
                                'status' => false,
                                'message' => 'Gagal kirim email'
                            ], 500);
                            // echo $this->mail->kirim($cekakun->Email, $data['type'], $body);
                        }
                    } else {
                        $this->response([
                            'status' => false,
                            'message' => 'Gagal kirim email dan kode'
                        ], 500);
                    }
                } else if ($data['type'] == "akun") {

                    $body['judul'] = "Informasi Akun";
                    $body['sapaan'] = "Halo kak, nih informasi akun kamu: ";
                    $body['isi'] = "Email: " . $cekakun->Email . ", Nama Akun: " . $cekakun->NamaAkun . ", No Telpon: " . $cekakun->NoTelpon;

                    if ($this->mail->kirim($cekakun->Email, $body['judul'], $body) == true) {
                        $this->response([
                            'status' => true,
                            'message' => 'Informasi akun telah dikirim ke email ' . $cekakun->Email
                        ], 200);
                    } else {
                        $this->response([
                            'status' => false,
                            'message' => 'Gagal kirim email'
                        ], 500);
                    }
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Gagal kirim email'
                ], 500);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Silahkan pilih type request'
            ], 500);
        }
    }
}
