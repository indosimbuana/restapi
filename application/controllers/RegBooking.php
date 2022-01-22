<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class RegBooking extends RestController
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

    public function index_post()
    {
        $d = file_get_contents('php://input');
        $data = json_decode($d, true);

        $date = date('Ymd');

        $this->load->model('mregbooking');

        $dt = array();
        $dt['idanggotakeluarga'] = $data['idanggotakeluarga'];

        $dt['bagian'] = $data['bagian'];
        $getb = $this->mregbooking->getBagian($data['bagian']);
        if ($getb) {
            $dt['namabagian'] = $getb->NamaBagian;
        } else {
            $dt['namabagian'] = "";
        }

        $dt['penjamin'] = $data['penjamin'];
        $getp = $this->mregbooking->getPenjamin($data['penjamin']);
        if ($getp) {
            $dt['namapenjamin'] = $getp->Nama;
        } else {
            $dt['namapenjamin'] = "";
        }

        $dt['nopenjamin'] = $data['nopenjamin'];
        $dt['norujukan'] = $data['norujukan'];

        $dt['dokter'] = $data['dokter'];
        $getd = $this->mregbooking->getDokter($data['dokter']);
        if ($getd) {
            $dt['namadokter'] = $getd->NamaDokter;
        } else {
            $dt['namadokter'] = "";
        }

        $dt['tanggal'] = $data['tanggal'];
        $dt['waktu'] = $data['waktu'];

        $t = new DateTime($data['tanggal']);

        switch ($t->format('D')) {
            case "Sun":
                $hari = "Minggu";
                break;
            case "Mon":
                $hari = "Senin";
                break;
            case "Tue":
                $hari = "Selasa";
                break;
            case "Wed":
                $hari = "Rabu";
                break;
            case "Thu":
                $hari = "Kamis";
                break;
            case "Fri":
                $hari = "Jumat";
                break;
            case "Sat":
                $hari = "Sabtu";
                break;
            default:
                $hari = "";
        }

        $cekbooking = $this->mregbooking->cekBooking($data['idanggotakeluarga'], $data['bagian'], str_replace("-", "", $data['tanggal']), $data['waktu']);
        if ($cekbooking) {
            $this->response([
                'status' => false,
                'message' => 'Maaf pasien ini sudah terdaftar di klinik yang sama pada tanggal yang sama'
            ], 400);
        } else {
            $jdwpoli = $this->mregbooking->getJamPoli($data['bagian'], $data['waktu'], $data['dokter']);

            // if ($jdwpoli) {
            $jp = date_format(date_create($dt['tanggal'] . ' ' . $jdwpoli->$hari), "Y-m-d H:i");
            $ttp = $hari . 'Tutup';
            $jptutup = date_format(date_create($dt['tanggal'] . ' ' . $jdwpoli->$ttp), "Y-m-d H:i");
            // }

            // Jam dilayani
            $tm = strtotime($jp);
            $w = 30; //Waktu konfirmasi pendaftaran sebelum jam buka poli (menit)
            $wlimit = 30; //Batas waktu pendaftaran setelah jam buka poli (menit)
            $prs = 3; //Lama proses pendaftaran
            $jmlloket = 2; //Jumlah loket yang melayani
            $jmldaftar = $this->mregbooking->hitungBookingPerPoli($data['bagian'], str_replace("-", "", $data['tanggal']), $data['waktu']); //Jumlah pendaftar poli
            $totaldaftar = floor(($prs * $jmldaftar) / $jmlloket);
            // $totaldaftar = floor(($prs * 300) / $jmlloket);
            $daftar = date("Y-m-d H:i", strtotime('-' . $w . ' minutes', $tm)); //Jam buka pendaftaran per poli
            $dilayani = date("Y-m-d H:i", strtotime('+' . $totaldaftar . ' minutes', strtotime($daftar))); //Jam dilayani
            $jamlebih = date("Y-m-d H:i", strtotime('+' . $wlimit . ' minutes', strtotime($jp))); //Jam dilayani setelah melebihi dari wlimit
            $selisih = strtotime($dilayani) - strtotime($daftar);
            // $dt['SELISIH'] = $selisih; //CEK
            // $dt['TOTALDETIK'] = $wlimit * 60;
            // $dt['DILAYANIII'] = $dilayani; //CEK
            // $dt['DL'] = $dilayani;
            // $dt['DF'] = $daftar;
            // $dt['JP'] = $jp;
            // $dt['JPTUTUP'] = $jptutup;
            // $dt['PENDAFTAR'] = $jmldaftar;
            // $dt['TOTALDAFTAR'] = $totaldaftar;

            if ($selisih > ($wlimit * 60)) {
                $dt['jamdilayani'] = $jamlebih;
            } else {
                if ($selisih < 0) {
                    $dt['jamdilayani'] = $daftar;
                } else {
                    $dt['jamdilayani'] = $dilayani;
                }
            }

            $hitungbooking = $this->mregbooking->hitungBooking(str_replace("-", "", $data['tanggal']));
            $dt['kodebooking'] = str_replace("-", "", $data['tanggal']) . str_pad($hitungbooking + 1, 4, "0", STR_PAD_LEFT);
            $dt['datetime'] = $daftar;

            // if ($data['waktu'] == 'P') {
            //     $jam = "07:00";
            // } else {
            //     $jam = "11:00";
            // }
            // $time = strtotime($jam);
            // $datetime = date("Y-m-d H:i", strtotime($dt['tanggal'] . $jam));

            if ($data['bagian'] == "6101" || $data['bagian'] == "6107") {
                // $hitungpendaftaran = $this->mregbooking->hitungPendaftaranObsgyn(str_replace("-", "", $data['tanggal']), $data['waktu']);
                $hitungpendaftaran = $this->mregbooking->hitungPendaftaranObsgyn(str_replace("-", "", $data['tanggal']), $data['waktu']);
                $hitungpoli = $this->mregbooking->hitungPoli($data['bagian'], str_replace("-", "", $data['tanggal']), $data['waktu']);
                if ($data['waktu'] == 'P') {
                    $dt['noantripendaftaran'] = "A" . str_pad($hitungpendaftaran + 1, 4, "0", STR_PAD_LEFT);
                } else {
                    $dt['noantripendaftaran'] = "C" . str_pad($hitungpendaftaran + 1, 4, "0", STR_PAD_LEFT);
                }
                $dt['noantripoli'] = str_pad($hitungpoli + 1, 3, "0", STR_PAD_LEFT);

                // $jml = $hitungpendaftaran;
                // $pelayanan = 3;
                // $wkt = $jml * $pelayanan;
                // $jamdilayani = date("H:i", strtotime('+' . $wkt . ' minutes', $time));
                // $dt['jamdilayani'] = date("Y-m-d", strtotime($dt['tanggal'])) . " " . $jamdilayani;
                // $dt['datetime'] = $datetime;
            } else {
                $hitungpendaftaran = $this->mregbooking->hitungPendaftaranLain(str_replace("-", "", $data['tanggal']), $data['waktu']);
                $hitungpoli = $this->mregbooking->hitungPoli($data['bagian'], str_replace("-", "", $data['tanggal']), $data['waktu']);
                if ($data['waktu'] == 'P') {
                    $dt['noantripendaftaran'] = "B" . str_pad($hitungpendaftaran + 1, 4, "0", STR_PAD_LEFT);
                } else {
                    $dt['noantripendaftaran'] = "D" . str_pad($hitungpendaftaran + 1, 4, "0", STR_PAD_LEFT);
                }
                $dt['noantripoli'] = str_pad($hitungpoli + 1, 3, "0", STR_PAD_LEFT);

                // $jml = $hitungpendaftaran;
                // $pelayanan = 3;
                // $wkt = $jml * $pelayanan;
                // $jamdilayani = date("H:i", strtotime('+' . $wkt . ' minutes', $time));
                // $dt['jamdilayani'] = date("Y-m-d", strtotime($dt['tanggal'])) . " " . $jamdilayani;
                // $dt['datetime'] = $datetime;
            }

            $antrianpool['idantri'] = date("YmdHis");
            $antrianpool['noantri'] = substr_replace($dt['noantripendaftaran'], " ", 1, 1);
            $antrianpool['tglantri'] = $dt['jamdilayani'];

            if ($this->mregbooking->simpanBooking($dt) && $this->mregbooking->simpanAntrianPool($antrianpool)) {
                $databooking['kodebooking'] = trim($dt['kodebooking']);
                $databooking['antriandaftar'] = trim($dt['noantripendaftaran']);
                $databooking['antrianpoli'] = trim($dt['noantripoli']);
                $databooking['jamdilayani'] = date_format(date_create($dt['jamdilayani']), "H:i");
                $databooking['idanggotakeluarga'] = $data['idanggotakeluarga'];
                $databooking['poli'] = trim($dt['namabagian']);
                $databooking['waktu'] = $dt['waktu'];
                $databooking['jampoli'] = isset($jp) ? $jp : '';
                $databooking['jamtutuppoli'] = isset($jptutup) ? $jptutup : '';
                $databooking['tglperiksa'] =  date_format(date_create($data['tanggal']), "d-m-Y");
                $databooking['penjamin'] = trim($dt['namapenjamin']);
                $databooking['nopenjamin'] = trim($dt['nopenjamin']);
                $databooking['norujukan'] = trim($dt['norujukan']);
                $databooking['dokter'] = trim($dt['namadokter']);
                $databooking['tgldaftar'] = date("d-m-Y");

                $this->load->model('mreganggota');
                $ag = $this->mreganggota->getAnggotaKeluarga($data['idanggotakeluarga']);

                if ($ag) {
                    $this->load->library('mail');
                    $body['judul'] = "Informasi Pendaftaran Rawat Jalan";
                    $body['sapaan'] = "Terimakasih telah melakukan pendaftaran online Pemeriksaan Rawat Jalan RSUD Panti Nugroho. Tunjukan Bukti Pendaftaran berikut ke petugas loket pendaftaran RSUD Panti Nugroho:";
                    $body['isi'] = "Kode Booking: " . $dt['kodebooking'] . ", Nomor Antrian Pendaftaran: " . $dt['noantripendaftaran'] . ", No Antri Poli: " . $dt['noantripoli'] . ", perkiraan jam dilayani " . date_format(date_create($dt['jamdilayani']), "H:i");

                    if ($ag->Email == '' || $ag->Email == NULL) {
                        $this->load->model('mregakun');
                        $ak = $this->mregakun->getAkunByNama($ag->idAkun);
                        $this->mail->kirim($ak->Email, $body['judul'], $body);
                    } else {
                        $this->mail->kirim($ag->Email, $body['judul'], $body);
                    }
                }


                $this->response([
                    'status' => true,
                    'message' => 'Berhasil Simpan Booking',
                    'data' => $databooking,
                    'antrianpool' => $antrianpool
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Gagal Simpan Booking'
                ], 400);
            }

            //---------- KEPERLUAN CEK -----------
            // $this->response([
            //     'status' => true,
            //     'message' => 'Berhasil Simpan Booking',
            //     'data' => $dt,
            //     'antrianpool' => $antrianpool
            // ], 200);
        }
    }
}
