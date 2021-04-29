<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class JknMobileAntrian extends RestController
{

    function __construct()
    {
        parent::__construct();
        $cek = $this->jkntoken->cek();
        if ($cek['status'] == false) {
            $this->response([
                'metadata' => [
                    'code' => 401,
                    'message' => $cek['message']
                ]
            ], 401);
        }
    }

    public function index_post()
    {
        $d = file_get_contents('php://input');
        $data = json_decode($d, true);

        $date = date('Ymd');

        $this->load->model('mmobilejkn');
        $this->load->model('mregbooking');
        $this->load->model('mreganggota');
        $this->load->model('mpilihtanggal');

        $dtreg = array();

        $dt = array();
        $dt['nomorkartu'] = isset($data['nomorkartu']) && $data['nomorkartu'] ? $data['nomorkartu'] : '';
        $dt['nik'] = isset($data['nik']) && $data['nik'] ? $data['nik'] : '';
        $dt['tanggalperiksa'] = isset($data['tanggalperiksa']) && $data['tanggalperiksa'] ? $data['tanggalperiksa'] : '';
        $dt['kodepoli'] = isset($data['kodepoli']) && $data['kodepoli'] ? $data['kodepoli'] : '';
        $dt['nomorreferensi'] = isset($data['nomorreferensi']) && $data['nomorreferensi'] ? $data['nomorreferensi'] : '';
        $dt['jenisreferensi'] = isset($data['jenisreferensi']) && $data['jenisreferensi'] ? $data['jenisreferensi'] : '';
        $dt['jenisrequest'] = isset($data['jenisrequest']) && $data['jenisrequest'] ? $data['jenisrequest'] : '';
        $dt['polieksekutif'] = $data['polieksekutif'];

        $this->load->library('validatedate');
        $this->load->library('vclaimapi');

        $libur = $this->mpilihtanggal->getHariLiburByTanggal($dt['tanggalperiksa']);

        if ($libur) {
            $this->response([
                'metadata' => [
                    'code' => 203,
                    'message' => 'Invalid tanggal periksa, hari libur'
                ]
            ], 203);
        } else {
            if (($dt['nomorkartu'] === NULL || $dt['nomorkartu'] === '') && strlen($dt['nomorkartu']) != 13) {
                $this->response([
                    'metadata' => [
                        'code' => 203,
                        'message' => 'Invalid No Kartu'
                    ]
                ], 203);
            } else {
                if (($dt['nik'] === NULL || $dt['nik'] === '') && strlen($dt['nik']) != 16) {
                    $this->response([
                        'metadata' => [
                            'code' => 203,
                            'message' => 'Invalid NIK'
                        ]
                    ], 203);
                } else {
                    if ($dt['nomorreferensi'] === NULL || $dt['nomorreferensi'] === '') {
                        $this->response([
                            'metadata' => [
                                'code' => 203,
                                'message' => 'Invalid No Referensi'
                            ]
                        ], 203);
                    } else {
                        $arrayref = array(1, 2);
                        if ($dt['jenisreferensi'] === NULL || $dt['jenisreferensi'] === '' || !in_array($dt['jenisreferensi'], $arrayref)) {
                            $this->response([
                                'metadata' => [
                                    'code' => 203,
                                    'message' => 'Invalid jenis referensi'
                                ]
                            ], 203);
                        } else {
                            $arrayreq = array(1, 2);
                            if ($dt['jenisrequest'] === NULL || $dt['jenisrequest'] === '' || !in_array($dt['jenisrequest'], $arrayreq)) {
                                $this->response([
                                    'metadata' => [
                                        'code' => 203,
                                        'message' => 'Invalid jenis request'
                                    ]
                                ], 203);
                            } else {
                                $arraypol = array(0, 1);
                                if ($dt['polieksekutif'] === NULL || $dt['polieksekutif'] === '' || !in_array($dt['polieksekutif'], $arraypol)) {
                                    $this->response([
                                        'metadata' => [
                                            'code' => 203,
                                            'message' => 'Invalid poli eksekutif'
                                        ]
                                    ], 203);
                                } else {
                                    if ($dt['polieksekutif'] === 1) {
                                        $this->response([
                                            'metadata' => [
                                                'code' => 203,
                                                'message' => 'Belum tersedia poli eksekutif'
                                            ]
                                        ], 203);
                                    } else {
                                        if ($dt['tanggalperiksa'] === NULL || $dt['tanggalperiksa'] === '' && strlen($dt['tanggalperiksa']) > 10 && $this->validatedate->cekDate($dt['tanggalperiksa']) === false) {
                                            $this->response([
                                                'metadata' => [
                                                    'code' => 203,
                                                    'message' => 'Invalid tanggal',
                                                    'date' => strlen($dt['tanggalperiksa'])
                                                ]
                                            ], 203);
                                        } else {
                                            if ($dt['tanggalperiksa'] < date('Y-m-d')) {
                                                $this->response([
                                                    'metadata' => [
                                                        'code' => 203,
                                                        'message' => 'Invalid tanggal'
                                                    ]
                                                ], 203);
                                            } else {
                                                $selisihdaftar = round((strtotime($dt['tanggalperiksa']) - time()) / (60 * 60 * 24));

                                                if ($selisihdaftar > 14) {
                                                    $this->response([
                                                        'metadata' => [
                                                            'code' => 203,
                                                            'message' => 'Invalid tanggal, jarak rencana tanggal pemeriksaan terlalu panjang dari sekarang, maks. 14 hari dari sekarang'
                                                        ]
                                                    ], 203);
                                                } else {
                                                    // Cek No Rujukan di Vclaim
                                                    // $aksesws = $this->vclaimapi->aksesws();

                                                    // $nomor = $dt['nomorreferensi'];

                                                    // $url = $aksesws['burl'] . $aksesws['service'] . 'Rujukan/' . $nomor;

                                                    // $ch = curl_init($url);
                                                    // curl_setopt($ch, CURLOPT_TIMEOUT, 50);
                                                    // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 50);
                                                    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                    // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                                    //     "Content-Type: application/json; charset=utf-8",
                                                    //     "X-cons-id: " . $aksesws['X-cons-id'],
                                                    //     "X-timestamp: " . $aksesws['X-timestamp'],
                                                    //     "X-signature: " . $aksesws['X-signature']
                                                    // ));
                                                    // $de = curl_exec($ch);
                                                    // $d = json_decode($de, true);
                                                    // curl_close($ch);

                                                    // if ($d['metaData']['code'] == '200') {

                                                    //     $tglkunjungan = $d['response']['rujukan']['tglKunjungan'];
                                                    //     $selisih = round((time() - strtotime($tglkunjungan)) / (60 * 60 * 24));

                                                    //     if ($selisih >= 90) {
                                                    //         $this->response([
                                                    //             'metadata' => [
                                                    //                 'code' => 203,
                                                    //                 'message' => 'Invalid rujukan, melebihi 90 hari, yaitu ' . $selisih . ' hari. Tgl Rujukan: ' . $tglkunjungan
                                                    //             ]
                                                    //         ], 203);
                                                    //     } else {
                                                    $t = new DateTime($dt['tanggalperiksa']);

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

                                                    $getkodepoli = $this->mmobilejkn->getKodePoli($dt['kodepoli']);

                                                    $cekanggotakeluarga = $this->mmobilejkn->cekPasienJkn($dt['nik']);

                                                    if ($getkodepoli) {

                                                        if ($getkodepoli->RuangId == '6101') {
                                                            $cekjampolipagi = $this->mmobilejkn->getJamPoliKebidanan('P', $hari);
                                                        } else {
                                                            $cekjampolipagi = $this->mmobilejkn->getJamPoli($getkodepoli->RuangId, 'P');
                                                        }

                                                        if ($cekjampolipagi) {
                                                            if ($cekjampolipagi->$hari != NULL || $cekjampolipagi->$hari !=  "00:00:00.00000") {

                                                                $cekreg = $this->mmobilejkn->cekRegPasienJkn($dt['nik'], $dt['tanggalperiksa'], $cekjampolipagi->KodeKlinik);

                                                                if ($cekreg) {
                                                                    $this->response([
                                                                        'metadata' => [
                                                                            'code' => 203,
                                                                            'message' => 'Pasien telah terdaftar di poli ini dgn hari yang sama'
                                                                        ]
                                                                    ], 203);
                                                                } else {
                                                                    $cekpsserverbaru = $this->mmobilejkn->getPasienServerBaru($dt['nomorkartu'], $dt['nik']);

                                                                    if ($cekpsserverbaru) {
                                                                        // Data Pasien
                                                                        if ($cekanggotakeluarga) {
                                                                            $dtpas['idanggotakeluarga'] = $cekanggotakeluarga->idAnggotaKeluarga;
                                                                        } else {
                                                                            // Reg Anggota Keluarga
                                                                            $dtpas['nopasien'] = trim($cekpsserverbaru->Nopasien);
                                                                            $dtpas['idanggotakeluarga'] = $date . rand(1000, 10000);
                                                                            $dtpas['idakun'] = 'JKN';
                                                                            $dtpas['hubungan'] = 'Orang Lain';
                                                                            $dtpas['namalengkap'] = trim($cekpsserverbaru->NamaPasien);
                                                                            $dtpas['namapanggilan'] = trim($cekpsserverbaru->NamaPasien);
                                                                            $dtpas['ktp'] = trim($cekpsserverbaru->NoKTP);
                                                                            $dtpas['jeniskelamin'] = trim($cekpsserverbaru->JenisKelamin);
                                                                            $dtpas['tempatlahir'] = '';
                                                                            $dtpas['tgllahir'] = trim($cekpsserverbaru->TglLahir);
                                                                            $dtpas['alamat'] = trim($cekpsserverbaru->AlamatPasien);
                                                                            $dtpas['rt'] = '';
                                                                            $dtpas['rw'] = '';
                                                                            $dtpas['provinsi'] = '';
                                                                            $dtpas['kabupaten'] = '';
                                                                            $dtpas['kecamatan'] = '';
                                                                            $dtpas['kodepos'] = '';
                                                                            $dtpas['agama'] = '';
                                                                            $dtpas['goloangandarah'] = '';
                                                                            $dtpas['pendidikan'] = '';
                                                                            $dtpas['statuskawin'] = '';
                                                                            $dtpas['pekerjaan'] = '';
                                                                            $dtpas['wni'] = '';
                                                                            $dtpas['negara'] = '';
                                                                            $dtpas['suku'] = '';
                                                                            $dtpas['bahasa'] = '';
                                                                            $dtpas['alergi'] = '';
                                                                            $dtpas['alamatkantor'] = '';
                                                                            $dtpas['telpkantor'] = '';
                                                                            $dtpas['namakeluarga'] = '';
                                                                            $dtpas['namaayah'] = '';
                                                                            $dtpas['namaibu'] = '';
                                                                            $dtpas['namasuamiistri'] = '';
                                                                            $dtpas['notelpon'] = trim($cekpsserverbaru->TlpPasien);
                                                                            $dtpas['email'] = '';

                                                                            $this->mmobilejkn->simpanPasienLama($dtpas);
                                                                        }
                                                                    } else {
                                                                        $cekpsserverlama = $this->mmobilejkn->getPasienServerLama($dt['nomorkartu'], $dt['nik']);

                                                                        if ($cekpsserverlama) {
                                                                            // Data Pasien
                                                                            if ($cekanggotakeluarga) {
                                                                                $dtpas['idanggotakeluarga'] = $cekanggotakeluarga->idAnggotaKeluarga;
                                                                            } else {
                                                                                // Reg Anggota Keluarga
                                                                                $dtpas['nopasien'] = trim($cekpsserverlama->Nopasien);
                                                                                $dtpas['idanggotakeluarga'] = $date . rand(1000, 10000);
                                                                                $dtpas['idakun'] = 'JKN';
                                                                                $dtpas['hubungan'] = 'Orang Lain';
                                                                                $dtpas['namalengkap'] = trim($cekpsserverlama->NamaPasien);
                                                                                $dtpas['namapanggilan'] = trim($cekpsserverlama->NamaPasien);
                                                                                $dtpas['ktp'] = trim($cekpsserverlama->NoKTP);
                                                                                $dtpas['jeniskelamin'] = trim($cekpsserverlama->JenisKelamin);
                                                                                $dtpas['tempatlahir'] = '';
                                                                                $dtpas['tgllahir'] = trim($cekpsserverlama->TglLahir);
                                                                                $dtpas['alamat'] = trim($cekpsserverlama->AlamatPasien);
                                                                                $dtpas['rt'] = '';
                                                                                $dtpas['rw'] = '';
                                                                                $dtpas['provinsi'] = '';
                                                                                $dtpas['kabupaten'] = '';
                                                                                $dtpas['kecamatan'] = '';
                                                                                $dtpas['kodepos'] = '';
                                                                                $dtpas['agama'] = '';
                                                                                $dtpas['goloangandarah'] = '';
                                                                                $dtpas['pendidikan'] = '';
                                                                                $dtpas['statuskawin'] = '';
                                                                                $dtpas['pekerjaan'] = '';
                                                                                $dtpas['wni'] = '';
                                                                                $dtpas['negara'] = '';
                                                                                $dtpas['suku'] = '';
                                                                                $dtpas['bahasa'] = '';
                                                                                $dtpas['alergi'] = '';
                                                                                $dtpas['alamatkantor'] = '';
                                                                                $dtpas['telpkantor'] = '';
                                                                                $dtpas['namakeluarga'] = '';
                                                                                $dtpas['namaayah'] = '';
                                                                                $dtpas['namaibu'] = '';
                                                                                $dtpas['namasuamiistri'] = '';
                                                                                $dtpas['notelpon'] = trim($cekpsserverlama->TlpPasien);
                                                                                $dtpas['email'] = '';

                                                                                $this->mmobilejkn->simpanPasienLama($dtpas);
                                                                            }
                                                                        } else {
                                                                            // Data Pasien
                                                                            if ($cekanggotakeluarga) {
                                                                                $dtpas['idanggotakeluarga'] = $cekanggotakeluarga->idAnggotaKeluarga;
                                                                            } else {
                                                                                // Reg Anggota Keluarga
                                                                                $dtpas['idanggotakeluarga'] = $date . rand(1000, 10000);
                                                                                $dtpas['idakun'] = 'JKN';
                                                                                $dtpas['hubungan'] = 'Orang Lain';
                                                                                $dtpas['namalengkap'] = '';
                                                                                $dtpas['namapanggilan'] = '';
                                                                                $dtpas['ktp'] = $dt['nik'];
                                                                                $dtpas['jeniskelamin'] = '';
                                                                                $dtpas['tempatlahir'] = '';
                                                                                $dtpas['tgllahir'] = '';
                                                                                $dtpas['alamat'] = '';
                                                                                $dtpas['rt'] = '';
                                                                                $dtpas['rw'] = '';
                                                                                $dtpas['provinsi'] = '';
                                                                                $dtpas['kabupaten'] = '';
                                                                                $dtpas['kecamatan'] = '';
                                                                                $dtpas['kodepos'] = '';
                                                                                $dtpas['agama'] = '';
                                                                                $dtpas['goloangandarah'] = '';
                                                                                $dtpas['pendidikan'] = '';
                                                                                $dtpas['statuskawin'] = '';
                                                                                $dtpas['pekerjaan'] = '';
                                                                                $dtpas['wni'] = '';
                                                                                $dtpas['negara'] = '';
                                                                                $dtpas['suku'] = '';
                                                                                $dtpas['bahasa'] = '';
                                                                                $dtpas['alergi'] = '';
                                                                                $dtpas['alamatkantor'] = '';
                                                                                $dtpas['telpkantor'] = '';
                                                                                $dtpas['namakeluarga'] = '';
                                                                                $dtpas['namaayah'] = '';
                                                                                $dtpas['namaibu'] = '';
                                                                                $dtpas['namasuamiistri'] = '';
                                                                                $dtpas['notelpon'] = '';
                                                                                $dtpas['email'] = '';

                                                                                $this->mmobilejkn->simpanPasienBaru($dtpas);
                                                                            }
                                                                        }
                                                                    }

                                                                    // Data Registrasi
                                                                    $dtreg['idanggotakeluarga'] = $dtpas['idanggotakeluarga'];

                                                                    $dtreg['bagian'] = $cekjampolipagi->KodeKlinik;
                                                                    $dtreg['namabagian'] = $cekjampolipagi->NamaBagian;

                                                                    $dtreg['penjamin'] = '';
                                                                    $dtreg['namapenjamin'] = '';

                                                                    $dtreg['nopenjamin'] = $dt['nomorkartu'];
                                                                    $dtreg['norujukan'] = $dt['nomorreferensi'];

                                                                    $dtreg['dokter'] = $cekjampolipagi->KodeDokter;
                                                                    $dtreg['namadokter'] = $cekjampolipagi->NamaDokter;

                                                                    $dtreg['waktu'] = 'P';

                                                                    $hitungbooking = $this->mregbooking->hitungBooking(str_replace("-", "", $dt['tanggalperiksa']));
                                                                    $dtreg['kodebooking'] = str_replace("-", "", $dt['tanggalperiksa']) . str_pad($hitungbooking + 1, 4, "0", STR_PAD_LEFT);
                                                                    $jam = "07:00";
                                                                    $time = strtotime($jam);
                                                                    $datetime = date("Y-m-d H:i", strtotime($dt['tanggalperiksa'] . $jam));

                                                                    if ($getkodepoli->RuangId == "6101" || $getkodepoli->RuangId == "6107") {
                                                                        // $hitungpendaftaran = $this->mregbooking->hitungPendaftaranObsgyn(str_replace("-", "", $dt['tanggalperiksa']), $dtreg['waktu']);
                                                                        $hitungpendaftaran = $this->mregbooking->hitungPendaftaranObsgyn(str_replace("-", "", $dt['tanggalperiksa']), $dtreg['waktu']);
                                                                        $hitungpoli = $this->mregbooking->hitungPoli($dtreg['bagian'], str_replace("-", "", $dt['tanggalperiksa']), $dtreg['waktu']);
                                                                        if ($dtreg['waktu'] == 'P') {
                                                                            $dtreg['noantripendaftaran'] = "A" . str_pad($hitungpendaftaran + 1, 4, "0", STR_PAD_LEFT);
                                                                        } else {
                                                                            $dtreg['noantripendaftaran'] = "C" . str_pad($hitungpendaftaran + 1, 4, "0", STR_PAD_LEFT);
                                                                        }
                                                                        $dtreg['noantripoli'] = str_pad($hitungpoli + 1, 3, "0", STR_PAD_LEFT);

                                                                        $jml = $hitungpendaftaran;
                                                                        $pelayanan = 3;
                                                                        $wkt = $jml * $pelayanan;
                                                                        $jamdilayani = date("H:i", strtotime('+' . $wkt . ' minutes', $time));
                                                                        $dtreg['jamdilayani'] = date("Y-m-d", strtotime($dt['tanggalperiksa'])) . " " . $jamdilayani;
                                                                        $dtreg['datetime'] = $datetime;
                                                                    } else {
                                                                        $hitungpendaftaran = $this->mregbooking->hitungPendaftaranLain(str_replace("-", "", $dt['tanggalperiksa']), $dtreg['waktu']);
                                                                        $hitungpoli = $this->mregbooking->hitungPoli($getkodepoli->RuangId, str_replace("-", "", $dt['tanggalperiksa']), $dtreg['waktu']);
                                                                        if ($dtreg['waktu'] == 'P') {
                                                                            $dtreg['noantripendaftaran'] = "B" . str_pad($hitungpendaftaran + 1, 4, "0", STR_PAD_LEFT);
                                                                        } else {
                                                                            $dtreg['noantripendaftaran'] = "D" . str_pad($hitungpendaftaran + 1, 4, "0", STR_PAD_LEFT);
                                                                        }
                                                                        $dtreg['noantripoli'] = str_pad($hitungpoli + 1, 3, "0", STR_PAD_LEFT);

                                                                        $jml = $hitungpendaftaran;
                                                                        $pelayanan = 3;
                                                                        $wkt = $jml * $pelayanan;
                                                                        $jamdilayani = date("H:i", strtotime('+' . $wkt . ' minutes', $time));
                                                                        $dtreg['jamdilayani'] = date("Y-m-d", strtotime($dt['tanggalperiksa'])) . " " . $jamdilayani;
                                                                        $dtreg['datetime'] = $datetime;
                                                                    }

                                                                    if ($this->mregbooking->simpanBooking($dtreg)) {
                                                                        $this->response([
                                                                            'metadata' => [
                                                                                'code' => 200,
                                                                                'message' => 'OK'
                                                                            ],
                                                                            'response' => [
                                                                                'nomorantrean' => trim($dtreg['noantripendaftaran']),
                                                                                'kodebooking' => trim($dtreg['kodebooking']),
                                                                                'jenisantrean' => 1,
                                                                                'estimasidilayani' => strtotime($dtreg['jamdilayani']) * 1000,
                                                                                'namapoli' => trim($dtreg['namabagian']),
                                                                                'namadokter' => trim($dtreg['namadokter'])
                                                                            ]
                                                                        ], 200);
                                                                    } else {
                                                                        $this->response([
                                                                            'metadata' => [
                                                                                'code' => 203,
                                                                                'message' => 'Gagal booking, silahkan periksa data dan coba lagi'
                                                                            ]
                                                                        ], 203);
                                                                    }
                                                                }
                                                            } else {
                                                                if ($getkodepoli->RuangId == '6101') {
                                                                    $cekjampolisore =  $this->mmobilejkn->getJamPoliKebidanan('S', $hari);
                                                                } else {
                                                                    $cekjampolisore = $this->mmobilejkn->getJamPoli($getkodepoli->RuangId, 'S');
                                                                }

                                                                if ($cekjampolisore) {
                                                                    if ($cekjampolisore->$hari != NULL || $cekjampolisore->$hari !=  "00:00:00.00000") {

                                                                        $cekreg = $this->mmobilejkn->cekRegPasienJkn($dt['nik'], $dt['tanggalperiksa'], $cekjampolisore->KodeKlinik);

                                                                        if ($cekreg) {
                                                                            $this->response([
                                                                                'metadata' => [
                                                                                    'code' => 203,
                                                                                    'message' => 'Pasien telah terdaftar di poli ini dgn hari yang sama'
                                                                                ]
                                                                            ], 203);
                                                                        } else {
                                                                            $cekpsserverbaru = $this->mmobilejkn->getPasienServerBaru($dt['nomorkartu'], $dt['nik']);

                                                                            if ($cekpsserverbaru) {
                                                                                // Data Pasien
                                                                                if ($cekanggotakeluarga) {
                                                                                    $dtpas['idanggotakeluarga'] = $cekanggotakeluarga->idAnggotaKeluarga;
                                                                                } else {
                                                                                    // Reg Anggota Keluarga
                                                                                    $dtpas['nopasien'] = trim($cekpsserverbaru->Nopasien);
                                                                                    $dtpas['idanggotakeluarga'] = $date . rand(1000, 10000);
                                                                                    $dtpas['idakun'] = 'JKN';
                                                                                    $dtpas['hubungan'] = 'Orang Lain';
                                                                                    $dtpas['namalengkap'] = trim($cekpsserverbaru->NamaPasien);
                                                                                    $dtpas['namapanggilan'] = trim($cekpsserverbaru->NamaPasien);
                                                                                    $dtpas['ktp'] = trim($cekpsserverbaru->NoKTP);
                                                                                    $dtpas['jeniskelamin'] = trim($cekpsserverbaru->JenisKelamin);
                                                                                    $dtpas['tempatlahir'] = '';
                                                                                    $dtpas['tgllahir'] = trim($cekpsserverbaru->TglLahir);
                                                                                    $dtpas['alamat'] = trim($cekpsserverbaru->AlamatPasien);
                                                                                    $dtpas['rt'] = '';
                                                                                    $dtpas['rw'] = '';
                                                                                    $dtpas['provinsi'] = '';
                                                                                    $dtpas['kabupaten'] = '';
                                                                                    $dtpas['kecamatan'] = '';
                                                                                    $dtpas['kodepos'] = '';
                                                                                    $dtpas['agama'] = '';
                                                                                    $dtpas['goloangandarah'] = '';
                                                                                    $dtpas['pendidikan'] = '';
                                                                                    $dtpas['statuskawin'] = '';
                                                                                    $dtpas['pekerjaan'] = '';
                                                                                    $dtpas['wni'] = '';
                                                                                    $dtpas['negara'] = '';
                                                                                    $dtpas['suku'] = '';
                                                                                    $dtpas['bahasa'] = '';
                                                                                    $dtpas['alergi'] = '';
                                                                                    $dtpas['alamatkantor'] = '';
                                                                                    $dtpas['telpkantor'] = '';
                                                                                    $dtpas['namakeluarga'] = '';
                                                                                    $dtpas['namaayah'] = '';
                                                                                    $dtpas['namaibu'] = '';
                                                                                    $dtpas['namasuamiistri'] = '';
                                                                                    $dtpas['notelpon'] = trim($cekpsserverbaru->TlpPasien);
                                                                                    $dtpas['email'] = '';

                                                                                    $this->mmobilejkn->simpanPasienLama($dtpas);
                                                                                }
                                                                            } else {
                                                                                $cekpsserverlama = $this->mmobilejkn->getPasienServerLama($dt['nomorkartu'], $dt['nik']);

                                                                                if ($cekpsserverlama) {
                                                                                    // Data Pasien
                                                                                    if ($cekanggotakeluarga) {
                                                                                        $dtpas['idanggotakeluarga'] = $cekanggotakeluarga->idAnggotaKeluarga;
                                                                                    } else {
                                                                                        // Reg Anggota Keluarga
                                                                                        $dtpas['nopasien'] = trim($cekpsserverlama->Nopasien);
                                                                                        $dtpas['idanggotakeluarga'] = $date . rand(1000, 10000);
                                                                                        $dtpas['idakun'] = 'JKN';
                                                                                        $dtpas['hubungan'] = 'Orang Lain';
                                                                                        $dtpas['namalengkap'] = trim($cekpsserverlama->NamaPasien);
                                                                                        $dtpas['namapanggilan'] = trim($cekpsserverlama->NamaPasien);
                                                                                        $dtpas['ktp'] = trim($cekpsserverlama->NoKTP);
                                                                                        $dtpas['jeniskelamin'] = trim($cekpsserverlama->JenisKelamin);
                                                                                        $dtpas['tempatlahir'] = '';
                                                                                        $dtpas['tgllahir'] = trim($cekpsserverlama->TglLahir);
                                                                                        $dtpas['alamat'] = trim($cekpsserverlama->AlamatPasien);
                                                                                        $dtpas['rt'] = '';
                                                                                        $dtpas['rw'] = '';
                                                                                        $dtpas['provinsi'] = '';
                                                                                        $dtpas['kabupaten'] = '';
                                                                                        $dtpas['kecamatan'] = '';
                                                                                        $dtpas['kodepos'] = '';
                                                                                        $dtpas['agama'] = '';
                                                                                        $dtpas['goloangandarah'] = '';
                                                                                        $dtpas['pendidikan'] = '';
                                                                                        $dtpas['statuskawin'] = '';
                                                                                        $dtpas['pekerjaan'] = '';
                                                                                        $dtpas['wni'] = '';
                                                                                        $dtpas['negara'] = '';
                                                                                        $dtpas['suku'] = '';
                                                                                        $dtpas['bahasa'] = '';
                                                                                        $dtpas['alergi'] = '';
                                                                                        $dtpas['alamatkantor'] = '';
                                                                                        $dtpas['telpkantor'] = '';
                                                                                        $dtpas['namakeluarga'] = '';
                                                                                        $dtpas['namaayah'] = '';
                                                                                        $dtpas['namaibu'] = '';
                                                                                        $dtpas['namasuamiistri'] = '';
                                                                                        $dtpas['notelpon'] = trim($cekpsserverlama->TlpPasien);
                                                                                        $dtpas['email'] = '';

                                                                                        $this->mmobilejkn->simpanPasienLama($dtpas);
                                                                                    }
                                                                                } else {
                                                                                    // Data Pasien
                                                                                    if ($cekanggotakeluarga) {
                                                                                        $dtpas['idanggotakeluarga'] = $cekanggotakeluarga->idAnggotaKeluarga;
                                                                                    } else {
                                                                                        // Reg Anggota Keluarga
                                                                                        $dtpas['idanggotakeluarga'] = $date . rand(1000, 10000);
                                                                                        $dtpas['idakun'] = 'JKN';
                                                                                        $dtpas['hubungan'] = 'Orang Lain';
                                                                                        $dtpas['namalengkap'] = '';
                                                                                        $dtpas['namapanggilan'] = '';
                                                                                        $dtpas['ktp'] = $dt['nik'];
                                                                                        $dtpas['jeniskelamin'] = '';
                                                                                        $dtpas['tempatlahir'] = '';
                                                                                        $dtpas['tgllahir'] = '';
                                                                                        $dtpas['alamat'] = '';
                                                                                        $dtpas['rt'] = '';
                                                                                        $dtpas['rw'] = '';
                                                                                        $dtpas['provinsi'] = '';
                                                                                        $dtpas['kabupaten'] = '';
                                                                                        $dtpas['kecamatan'] = '';
                                                                                        $dtpas['kodepos'] = '';
                                                                                        $dtpas['agama'] = '';
                                                                                        $dtpas['goloangandarah'] = '';
                                                                                        $dtpas['pendidikan'] = '';
                                                                                        $dtpas['statuskawin'] = '';
                                                                                        $dtpas['pekerjaan'] = '';
                                                                                        $dtpas['wni'] = '';
                                                                                        $dtpas['negara'] = '';
                                                                                        $dtpas['suku'] = '';
                                                                                        $dtpas['bahasa'] = '';
                                                                                        $dtpas['alergi'] = '';
                                                                                        $dtpas['alamatkantor'] = '';
                                                                                        $dtpas['telpkantor'] = '';
                                                                                        $dtpas['namakeluarga'] = '';
                                                                                        $dtpas['namaayah'] = '';
                                                                                        $dtpas['namaibu'] = '';
                                                                                        $dtpas['namasuamiistri'] = '';
                                                                                        $dtpas['notelpon'] = '';
                                                                                        $dtpas['email'] = '';

                                                                                        $this->mmobilejkn->simpanPasienBaru($dtpas);
                                                                                    }
                                                                                }
                                                                            }

                                                                            // Data Registrasi
                                                                            $dtreg['idanggotakeluarga'] = $dtpas['idanggotakeluarga'];

                                                                            $dtreg['bagian'] = $cekjampolisore->KodeKlinik;
                                                                            $dtreg['namabagian'] = $cekjampolisore->NamaBagian;

                                                                            $dtreg['penjamin'] = '';
                                                                            $dtreg['namapenjamin'] = '';

                                                                            $dtreg['nopenjamin'] = $dt['nomorkartu'];
                                                                            $dtreg['norujukan'] = $dt['nomorreferensi'];

                                                                            $dtreg['dokter'] = $cekjampolisore->KodeDokter;
                                                                            $dtreg['namadokter'] = $cekjampolisore->NamaDokter;

                                                                            $dtreg['waktu'] = 'P';

                                                                            $hitungbooking = $this->mregbooking->hitungBooking(str_replace("-", "", $dt['tanggalperiksa']));
                                                                            $dtreg['kodebooking'] = str_replace("-", "", $dt['tanggalperiksa']) . str_pad($hitungbooking + 1, 4, "0", STR_PAD_LEFT);
                                                                            $jam = "07:00";
                                                                            $time = strtotime($jam);
                                                                            $datetime = date("Y-m-d H:i", strtotime($dt['tanggalperiksa'] . $jam));

                                                                            if ($getkodepoli->RuangId == "6101" || $getkodepoli->RuangId == "6107") {
                                                                                // $hitungpendaftaran = $this->mregbooking->hitungPendaftaranObsgyn(str_replace("-", "", $dt['tanggalperiksa']), $dtreg['waktu']);
                                                                                $hitungpendaftaran = $this->mregbooking->hitungPendaftaranObsgyn(str_replace("-", "", $dt['tanggalperiksa']), $dtreg['waktu']);
                                                                                $hitungpoli = $this->mregbooking->hitungPoli($dtreg['bagian'], str_replace("-", "", $dt['tanggalperiksa']), $dtreg['waktu']);
                                                                                if ($dtreg['waktu'] == 'P') {
                                                                                    $dtreg['noantripendaftaran'] = "A" . str_pad($hitungpendaftaran + 1, 4, "0", STR_PAD_LEFT);
                                                                                } else {
                                                                                    $dtreg['noantripendaftaran'] = "C" . str_pad($hitungpendaftaran + 1, 4, "0", STR_PAD_LEFT);
                                                                                }
                                                                                $dtreg['noantripoli'] = str_pad($hitungpoli + 1, 3, "0", STR_PAD_LEFT);

                                                                                $jml = $hitungpendaftaran;
                                                                                $pelayanan = 3;
                                                                                $wkt = $jml * $pelayanan;
                                                                                $jamdilayani = date("H:i", strtotime('+' . $wkt . ' minutes', $time));
                                                                                $dtreg['jamdilayani'] = date("Y-m-d", strtotime($dt['tanggalperiksa'])) . " " . $jamdilayani;
                                                                                $dtreg['datetime'] = $datetime;
                                                                            } else {
                                                                                $hitungpendaftaran = $this->mregbooking->hitungPendaftaranLain(str_replace("-", "", $dt['tanggalperiksa']), $dtreg['waktu']);
                                                                                $hitungpoli = $this->mregbooking->hitungPoli($getkodepoli->RuangId, str_replace("-", "", $dt['tanggalperiksa']), $dtreg['waktu']);
                                                                                if ($dtreg['waktu'] == 'P') {
                                                                                    $dtreg['noantripendaftaran'] = "B" . str_pad($hitungpendaftaran + 1, 4, "0", STR_PAD_LEFT);
                                                                                } else {
                                                                                    $dtreg['noantripendaftaran'] = "D" . str_pad($hitungpendaftaran + 1, 4, "0", STR_PAD_LEFT);
                                                                                }
                                                                                $dtreg['noantripoli'] = str_pad($hitungpoli + 1, 3, "0", STR_PAD_LEFT);

                                                                                $jml = $hitungpendaftaran;
                                                                                $pelayanan = 3;
                                                                                $wkt = $jml * $pelayanan;
                                                                                $jamdilayani = date("H:i", strtotime('+' . $wkt . ' minutes', $time));
                                                                                $dtreg['jamdilayani'] = date("Y-m-d", strtotime($dt['tanggalperiksa'])) . " " . $jamdilayani;
                                                                                $dtreg['datetime'] = $datetime;
                                                                            }

                                                                            if ($this->mregbooking->simpanBooking($dtreg)) {
                                                                                $this->response([
                                                                                    'metadata' => [
                                                                                        'code' => 200,
                                                                                        'message' => 'OK'
                                                                                    ],
                                                                                    'response' => [
                                                                                        'nomorantrean' => trim($dtreg['noantripendaftaran']),
                                                                                        'kodebooking' => trim($dtreg['kodebooking']),
                                                                                        'jenisantrean' => 1,
                                                                                        'estimasidilayani' => strtotime($dtreg['jamdilayani']) * 1000,
                                                                                        'namapoli' => trim($dtreg['namabagian']),
                                                                                        'namadokter' => trim($dtreg['namadokter'])
                                                                                    ]
                                                                                ], 200);
                                                                            } else {
                                                                                $this->response([
                                                                                    'metadata' => [
                                                                                        'code' => 203,
                                                                                        'message' => 'Gagal booking, silahkan periksa data dan coba lagi'
                                                                                    ]
                                                                                ], 203);
                                                                            }
                                                                        }
                                                                    } else {
                                                                        $this->response([
                                                                            'metadata' => [
                                                                                'code' => 203,
                                                                                'message' => 'Poli Libur'
                                                                            ]
                                                                        ], 203);
                                                                    }
                                                                } else {
                                                                    $this->response([
                                                                        'metadata' => [
                                                                            'code' => 203,
                                                                            'message' => 'Poli Libur'
                                                                        ]
                                                                    ], 203);
                                                                }
                                                            }
                                                        } else {
                                                            $this->response([
                                                                'metadata' => [
                                                                    'code' => 203,
                                                                    'message' => 'Poli Libur'
                                                                ]
                                                            ], 203);
                                                        }
                                                    } else {
                                                        $this->response([
                                                            'metadata' => [
                                                                'code' => 203,
                                                                'message' => 'Kode Poli Tidak Terdaftar'
                                                            ]
                                                        ], 203);
                                                    }
                                                    //     }
                                                    // } else {
                                                    //     $url2 = $aksesws['burl'] . $aksesws['service'] . 'Rujukan/RS/' . $nomor;

                                                    //     $ch2 = curl_init($url2);
                                                    //     curl_setopt($ch2, CURLOPT_TIMEOUT, 50);
                                                    //     curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, 50);
                                                    //     curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
                                                    //     curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
                                                    //         "Content-Type: application/json; charset=utf-8",
                                                    //         "X-cons-id: " . $aksesws['X-cons-id'],
                                                    //         "X-timestamp: " . $aksesws['X-timestamp'],
                                                    //         "X-signature: " . $aksesws['X-signature']
                                                    //     ));
                                                    //     $de2 = curl_exec($ch2);
                                                    //     $d2 = json_decode($de2, true);
                                                    //     curl_close($ch2);

                                                    //     if ($d2['metaData']['code'] == '200') {

                                                    //         $tglkunjungan = $d2['response']['rujukan']['tglKunjungan'];
                                                    //         $selisih = round((time() - strtotime($tglkunjungan)) / (60 * 60 * 24));

                                                    //         if ($selisih >= 90) {
                                                    //             $this->response([
                                                    //                 'metadata' => [
                                                    //                     'code' => 203,
                                                    //                     'message' => 'Invalid rujukan, melebihi 90 hari, ' . $selisih . ' hari. Tgl Rujukan: ' . $tglkunjungan
                                                    //                 ]
                                                    //             ], 203);
                                                    //         } else {
                                                    //             $t = new DateTime($dt['tanggalperiksa']);

                                                    //             switch ($t->format('D')) {
                                                    //                 case "Sun":
                                                    //                     $hari = "Minggu";
                                                    //                     break;
                                                    //                 case "Mon":
                                                    //                     $hari = "Senin";
                                                    //                     break;
                                                    //                 case "Tue":
                                                    //                     $hari = "Selasa";
                                                    //                     break;
                                                    //                 case "Wed":
                                                    //                     $hari = "Rabu";
                                                    //                     break;
                                                    //                 case "Thu":
                                                    //                     $hari = "Kamis";
                                                    //                     break;
                                                    //                 case "Fri":
                                                    //                     $hari = "Jumat";
                                                    //                     break;
                                                    //                 case "Sat":
                                                    //                     $hari = "Sabtu";
                                                    //                     break;
                                                    //                 default:
                                                    //                     $hari = "";
                                                    //             }

                                                    //             $getkodepoli = $this->mmobilejkn->getKodePoli($dt['kodepoli']);

                                                    //             $cekanggotakeluarga = $this->mmobilejkn->cekPasienJkn($dt['nik']);

                                                    //             if ($getkodepoli) {

                                                    //                 if ($getkodepoli->RuangId == '6101') {
                                                    //                     $cekjampolipagi = $this->mmobilejkn->getJamPoliKebidanan('P', $hari);
                                                    //                 } else {
                                                    //                     $cekjampolipagi = $this->mmobilejkn->getJamPoli($getkodepoli->RuangId, 'P', $hari);
                                                    //                 }

                                                    //                 if ($cekjampolipagi) {
                                                    //                     if ($cekjampolipagi->$hari != NULL || $cekjampolipagi->$hari !=  "00:00:00.00000") {

                                                    //                         $cekreg = $this->mmobilejkn->cekRegPasienJkn($dt['nik'], $dt['tanggalperiksa'], $cekjampolipagi->KodeKlinik);

                                                    //                         if ($cekreg) {
                                                    //                             $this->response([
                                                    //                                 'metadata' => [
                                                    //                                     'code' => 203,
                                                    //                                     'message' => 'Pasien telah terdaftar di poli ini dgn hari yang sama'
                                                    //                                 ]
                                                    //                             ], 203);
                                                    //                         } else {
                                                    //                             $cekpsserverbaru = $this->mmobilejkn->getPasienServerBaru($dt['nomorkartu'], $dt['nik']);

                                                    //                             if ($cekpsserverbaru) {
                                                    //                                 // Data Pasien
                                                    //                                 if ($cekanggotakeluarga) {
                                                    //                                     $dtpas['idanggotakeluarga'] = $cekanggotakeluarga->idAnggotaKeluarga;
                                                    //                                 } else {
                                                    //                                     // Reg Anggota Keluarga
                                                    //                                     $dtpas['nopasien'] = trim($cekpsserverbaru->Nopasien);
                                                    //                                     $dtpas['idanggotakeluarga'] = $date . rand(1000, 10000);
                                                    //                                     $dtpas['idakun'] = 'JKN';
                                                    //                                     $dtpas['hubungan'] = 'Orang Lain';
                                                    //                                     $dtpas['namalengkap'] = trim($cekpsserverbaru->NamaPasien);
                                                    //                                     $dtpas['namapanggilan'] = trim($cekpsserverbaru->NamaPasien);
                                                    //                                     $dtpas['ktp'] = trim($cekpsserverbaru->NoKTP);
                                                    //                                     $dtpas['jeniskelamin'] = trim($cekpsserverbaru->JenisKelamin);
                                                    //                                     $dtpas['tempatlahir'] = '';
                                                    //                                     $dtpas['tgllahir'] = trim($cekpsserverbaru->TglLahir);
                                                    //                                     $dtpas['alamat'] = trim($cekpsserverbaru->AlamatPasien);
                                                    //                                     $dtpas['rt'] = '';
                                                    //                                     $dtpas['rw'] = '';
                                                    //                                     $dtpas['provinsi'] = '';
                                                    //                                     $dtpas['kabupaten'] = '';
                                                    //                                     $dtpas['kecamatan'] = '';
                                                    //                                     $dtpas['kodepos'] = '';
                                                    //                                     $dtpas['agama'] = '';
                                                    //                                     $dtpas['goloangandarah'] = '';
                                                    //                                     $dtpas['pendidikan'] = '';
                                                    //                                     $dtpas['statuskawin'] = '';
                                                    //                                     $dtpas['pekerjaan'] = '';
                                                    //                                     $dtpas['wni'] = '';
                                                    //                                     $dtpas['negara'] = '';
                                                    //                                     $dtpas['suku'] = '';
                                                    //                                     $dtpas['bahasa'] = '';
                                                    //                                     $dtpas['alergi'] = '';
                                                    //                                     $dtpas['alamatkantor'] = '';
                                                    //                                     $dtpas['telpkantor'] = '';
                                                    //                                     $dtpas['namakeluarga'] = '';
                                                    //                                     $dtpas['namaayah'] = '';
                                                    //                                     $dtpas['namaibu'] = '';
                                                    //                                     $dtpas['namasuamiistri'] = '';
                                                    //                                     $dtpas['notelpon'] = trim($cekpsserverbaru->TlpPasien);
                                                    //                                     $dtpas['email'] = '';

                                                    //                                     $this->mmobilejkn->simpanPasienLama($dtpas);
                                                    //                                 }
                                                    //                             } else {
                                                    //                                 $cekpsserverlama = $this->mmobilejkn->getPasienServerLama($dt['nomorkartu'], $dt['nik']);

                                                    //                                 if ($cekpsserverlama) {
                                                    //                                     // Data Pasien
                                                    //                                     if ($cekanggotakeluarga) {
                                                    //                                         $dtpas['idanggotakeluarga'] = $cekanggotakeluarga->idAnggotaKeluarga;
                                                    //                                     } else {
                                                    //                                         // Reg Anggota Keluarga
                                                    //                                         $dtpas['nopasien'] = trim($cekpsserverlama->Nopasien);
                                                    //                                         $dtpas['idanggotakeluarga'] = $date . rand(1000, 10000);
                                                    //                                         $dtpas['idakun'] = 'JKN';
                                                    //                                         $dtpas['hubungan'] = 'Orang Lain';
                                                    //                                         $dtpas['namalengkap'] = trim($cekpsserverlama->NamaPasien);
                                                    //                                         $dtpas['namapanggilan'] = trim($cekpsserverlama->NamaPasien);
                                                    //                                         $dtpas['ktp'] = trim($cekpsserverlama->NoKTP);
                                                    //                                         $dtpas['jeniskelamin'] = trim($cekpsserverlama->JenisKelamin);
                                                    //                                         $dtpas['tempatlahir'] = '';
                                                    //                                         $dtpas['tgllahir'] = trim($cekpsserverlama->TglLahir);
                                                    //                                         $dtpas['alamat'] = trim($cekpsserverlama->AlamatPasien);
                                                    //                                         $dtpas['rt'] = '';
                                                    //                                         $dtpas['rw'] = '';
                                                    //                                         $dtpas['provinsi'] = '';
                                                    //                                         $dtpas['kabupaten'] = '';
                                                    //                                         $dtpas['kecamatan'] = '';
                                                    //                                         $dtpas['kodepos'] = '';
                                                    //                                         $dtpas['agama'] = '';
                                                    //                                         $dtpas['goloangandarah'] = '';
                                                    //                                         $dtpas['pendidikan'] = '';
                                                    //                                         $dtpas['statuskawin'] = '';
                                                    //                                         $dtpas['pekerjaan'] = '';
                                                    //                                         $dtpas['wni'] = '';
                                                    //                                         $dtpas['negara'] = '';
                                                    //                                         $dtpas['suku'] = '';
                                                    //                                         $dtpas['bahasa'] = '';
                                                    //                                         $dtpas['alergi'] = '';
                                                    //                                         $dtpas['alamatkantor'] = '';
                                                    //                                         $dtpas['telpkantor'] = '';
                                                    //                                         $dtpas['namakeluarga'] = '';
                                                    //                                         $dtpas['namaayah'] = '';
                                                    //                                         $dtpas['namaibu'] = '';
                                                    //                                         $dtpas['namasuamiistri'] = '';
                                                    //                                         $dtpas['notelpon'] = trim($cekpsserverlama->TlpPasien);
                                                    //                                         $dtpas['email'] = '';

                                                    //                                         $this->mmobilejkn->simpanPasienLama($dtpas);
                                                    //                                     }
                                                    //                                 } else {
                                                    //                                     // Data Pasien
                                                    //                                     if ($cekanggotakeluarga) {
                                                    //                                         $dtpas['idanggotakeluarga'] = $cekanggotakeluarga->idAnggotaKeluarga;
                                                    //                                     } else {
                                                    //                                         // Reg Anggota Keluarga
                                                    //                                         $dtpas['idanggotakeluarga'] = $date . rand(1000, 10000);
                                                    //                                         $dtpas['idakun'] = 'JKN';
                                                    //                                         $dtpas['hubungan'] = 'Orang Lain';
                                                    //                                         $dtpas['namalengkap'] = '';
                                                    //                                         $dtpas['namapanggilan'] = '';
                                                    //                                         $dtpas['ktp'] = $dt['nik'];
                                                    //                                         $dtpas['jeniskelamin'] = '';
                                                    //                                         $dtpas['tempatlahir'] = '';
                                                    //                                         $dtpas['tgllahir'] = '';
                                                    //                                         $dtpas['alamat'] = '';
                                                    //                                         $dtpas['rt'] = '';
                                                    //                                         $dtpas['rw'] = '';
                                                    //                                         $dtpas['provinsi'] = '';
                                                    //                                         $dtpas['kabupaten'] = '';
                                                    //                                         $dtpas['kecamatan'] = '';
                                                    //                                         $dtpas['kodepos'] = '';
                                                    //                                         $dtpas['agama'] = '';
                                                    //                                         $dtpas['goloangandarah'] = '';
                                                    //                                         $dtpas['pendidikan'] = '';
                                                    //                                         $dtpas['statuskawin'] = '';
                                                    //                                         $dtpas['pekerjaan'] = '';
                                                    //                                         $dtpas['wni'] = '';
                                                    //                                         $dtpas['negara'] = '';
                                                    //                                         $dtpas['suku'] = '';
                                                    //                                         $dtpas['bahasa'] = '';
                                                    //                                         $dtpas['alergi'] = '';
                                                    //                                         $dtpas['alamatkantor'] = '';
                                                    //                                         $dtpas['telpkantor'] = '';
                                                    //                                         $dtpas['namakeluarga'] = '';
                                                    //                                         $dtpas['namaayah'] = '';
                                                    //                                         $dtpas['namaibu'] = '';
                                                    //                                         $dtpas['namasuamiistri'] = '';
                                                    //                                         $dtpas['notelpon'] = '';
                                                    //                                         $dtpas['email'] = '';

                                                    //                                         $this->mmobilejkn->simpanPasienBaru($dtpas);
                                                    //                                     }
                                                    //                                 }
                                                    //                             }

                                                    //                             // Data Registrasi
                                                    //                             $dtreg['idanggotakeluarga'] = $dtpas['idanggotakeluarga'];

                                                    //                             $dtreg['bagian'] = $cekjampolipagi->KodeKlinik;
                                                    //                             $dtreg['namabagian'] = $cekjampolipagi->NamaBagian;

                                                    //                             $dtreg['penjamin'] = '';
                                                    //                             $dtreg['namapenjamin'] = '';

                                                    //                             $dtreg['nopenjamin'] = $dt['nomorkartu'];
                                                    //                             $dtreg['norujukan'] = $dt['nomorreferensi'];

                                                    //                             $dtreg['dokter'] = $cekjampolipagi->KodeDokter;
                                                    //                             $dtreg['namadokter'] = $cekjampolipagi->NamaDokter;

                                                    //                             $dtreg['waktu'] = 'P';

                                                    //                             $hitungbooking = $this->mregbooking->hitungBooking(str_replace("-", "", $dt['tanggalperiksa']));
                                                    //                             $dtreg['kodebooking'] = str_replace("-", "", $dt['tanggalperiksa']) . str_pad($hitungbooking + 1, 4, "0", STR_PAD_LEFT);
                                                    //                             $jam = "07:00";
                                                    //                             $time = strtotime($jam);
                                                    //                             $datetime = date("Y-m-d H:i", strtotime($dt['tanggalperiksa'] . $jam));

                                                    //                             if ($getkodepoli->RuangId == "6101" || $getkodepoli->RuangId == "6101") {
                                                    //                                 $hitungpendaftaran = $this->mregbooking->hitungPendaftaranObsgyn(str_replace("-", "", $dt['tanggalperiksa']), $dtreg['waktu']);
                                                    //                                 $hitungpoli = $this->mregbooking->hitungPoli($dtreg['bagian'], str_replace("-", "", $dt['tanggalperiksa']), $dtreg['waktu']);
                                                    //                                 $dtreg['noantripendaftaran'] = "A" . str_pad($hitungpendaftaran + 1, 4, "0", STR_PAD_LEFT);
                                                    //                                 $dtreg['noantripoli'] = str_pad($hitungpoli + 1, 3, "0", STR_PAD_LEFT);

                                                    //                                 $jml = $hitungpendaftaran;
                                                    //                                 $pelayanan = 3;
                                                    //                                 $wkt = $jml * $pelayanan;
                                                    //                                 $jamdilayani = date("H:i", strtotime('+' . $wkt . ' minutes', $time));
                                                    //                                 $dtreg['jamdilayani'] = date("Y-m-d", strtotime($dt['tanggalperiksa'])) . " " . $jamdilayani;
                                                    //                                 $dtreg['datetime'] = $datetime;
                                                    //                             } else {
                                                    //                                 $hitungpendaftaran = $this->mregbooking->hitungPendaftaranLain(str_replace("-", "", $dt['tanggalperiksa']));
                                                    //                                 $hitungpoli = $this->mregbooking->hitungPoli($getkodepoli->RuangId, str_replace("-", "", $dt['tanggalperiksa']), $dtreg['waktu']);
                                                    //                                 $dtreg['noantripendaftaran'] = "B" . str_pad($hitungpendaftaran + 1, 4, "0", STR_PAD_LEFT);
                                                    //                                 $dtreg['noantripoli'] = str_pad($hitungpoli + 1, 3, "0", STR_PAD_LEFT);

                                                    //                                 $jml = $hitungpendaftaran;
                                                    //                                 $pelayanan = 3;
                                                    //                                 $wkt = $jml * $pelayanan;
                                                    //                                 $jamdilayani = date("H:i", strtotime('+' . $wkt . ' minutes', $time));
                                                    //                                 $dtreg['jamdilayani'] = date("Y-m-d", strtotime($dt['tanggalperiksa'])) . " " . $jamdilayani;
                                                    //                                 $dtreg['datetime'] = $datetime;
                                                    //                             }

                                                    //                             if ($this->mregbooking->simpanBooking($dtreg)) {
                                                    //                                 $this->response([
                                                    //                                     'metadata' => [
                                                    //                                         'code' => 200,
                                                    //                                         'message' => 'OK'
                                                    //                                     ],
                                                    //                                     'response' => [
                                                    //                                         'nomorantrean' => trim($dtreg['noantripendaftaran']),
                                                    //                                         'kodebooking' => trim($dtreg['kodebooking']),
                                                    //                                         'jenisantrean' => 1,
                                                    //                                         'estimasidilayani' => strtotime($dtreg['jamdilayani']) * 1000,
                                                    //                                         'namapoli' => trim($dtreg['namabagian']),
                                                    //                                         'namadokter' => trim($dtreg['namadokter'])
                                                    //                                     ]
                                                    //                                 ], 200);
                                                    //                             } else {
                                                    //                                 $this->response([
                                                    //                                     'metadata' => [
                                                    //                                         'code' => 203,
                                                    //                                         'message' => 'Gagal booking, silahkan periksa data dan coba lagi'
                                                    //                                     ]
                                                    //                                 ], 203);
                                                    //                             }
                                                    //                         }
                                                    //                     } else {
                                                    //                         if ($getkodepoli->RuangId == '6101') {
                                                    //                             $cekjampolisore =  $this->mmobilejkn->getJamPoliKebidanan('S', $hari);
                                                    //                         } else {
                                                    //                             $cekjampolisore = $this->mmobilejkn->getJamPoli($getkodepoli->RuangId, 'S', $hari);
                                                    //                         }

                                                    //                         if ($cekjampolisore) {
                                                    //                             if ($cekjampolisore->$hari != NULL || $cekjampolisore->$hari !=  "00:00:00.00000") {

                                                    //                                 $cekreg = $this->mmobilejkn->cekRegPasienJkn($dt['nik'], $dt['tanggalperiksa'], $cekjampolisore->KodeKlinik);

                                                    //                                 if ($cekreg) {
                                                    //                                     $this->response([
                                                    //                                         'metadata' => [
                                                    //                                             'code' => 203,
                                                    //                                             'message' => 'Pasien telah terdaftar di poli ini dgn hari yang sama'
                                                    //                                         ]
                                                    //                                     ], 203);
                                                    //                                 } else {
                                                    //                                     $cekpsserverbaru = $this->mmobilejkn->getPasienServerBaru($dt['nomorkartu'], $dt['nik']);

                                                    //                                     if ($cekpsserverbaru) {
                                                    //                                         // Data Pasien
                                                    //                                         if ($cekanggotakeluarga) {
                                                    //                                             $dtpas['idanggotakeluarga'] = $cekanggotakeluarga->idAnggotaKeluarga;
                                                    //                                         } else {
                                                    //                                             // Reg Anggota Keluarga
                                                    //                                             $dtpas['nopasien'] = trim($cekpsserverbaru->Nopasien);
                                                    //                                             $dtpas['idanggotakeluarga'] = $date . rand(1000, 10000);
                                                    //                                             $dtpas['idakun'] = 'JKN';
                                                    //                                             $dtpas['hubungan'] = 'Orang Lain';
                                                    //                                             $dtpas['namalengkap'] = trim($cekpsserverbaru->NamaPasien);
                                                    //                                             $dtpas['namapanggilan'] = trim($cekpsserverbaru->NamaPasien);
                                                    //                                             $dtpas['ktp'] = trim($cekpsserverbaru->NoKTP);
                                                    //                                             $dtpas['jeniskelamin'] = trim($cekpsserverbaru->JenisKelamin);
                                                    //                                             $dtpas['tempatlahir'] = '';
                                                    //                                             $dtpas['tgllahir'] = trim($cekpsserverbaru->TglLahir);
                                                    //                                             $dtpas['alamat'] = trim($cekpsserverbaru->AlamatPasien);
                                                    //                                             $dtpas['rt'] = '';
                                                    //                                             $dtpas['rw'] = '';
                                                    //                                             $dtpas['provinsi'] = '';
                                                    //                                             $dtpas['kabupaten'] = '';
                                                    //                                             $dtpas['kecamatan'] = '';
                                                    //                                             $dtpas['kodepos'] = '';
                                                    //                                             $dtpas['agama'] = '';
                                                    //                                             $dtpas['goloangandarah'] = '';
                                                    //                                             $dtpas['pendidikan'] = '';
                                                    //                                             $dtpas['statuskawin'] = '';
                                                    //                                             $dtpas['pekerjaan'] = '';
                                                    //                                             $dtpas['wni'] = '';
                                                    //                                             $dtpas['negara'] = '';
                                                    //                                             $dtpas['suku'] = '';
                                                    //                                             $dtpas['bahasa'] = '';
                                                    //                                             $dtpas['alergi'] = '';
                                                    //                                             $dtpas['alamatkantor'] = '';
                                                    //                                             $dtpas['telpkantor'] = '';
                                                    //                                             $dtpas['namakeluarga'] = '';
                                                    //                                             $dtpas['namaayah'] = '';
                                                    //                                             $dtpas['namaibu'] = '';
                                                    //                                             $dtpas['namasuamiistri'] = '';
                                                    //                                             $dtpas['notelpon'] = trim($cekpsserverbaru->TlpPasien);
                                                    //                                             $dtpas['email'] = '';

                                                    //                                             $this->mmobilejkn->simpanPasienLama($dtpas);
                                                    //                                         }
                                                    //                                     } else {
                                                    //                                         $cekpsserverlama = $this->mmobilejkn->getPasienServerLama($dt['nomorkartu'], $dt['nik']);

                                                    //                                         if ($cekpsserverlama) {
                                                    //                                             // Data Pasien
                                                    //                                             if ($cekanggotakeluarga) {
                                                    //                                                 $dtpas['idanggotakeluarga'] = $cekanggotakeluarga->idAnggotaKeluarga;
                                                    //                                             } else {
                                                    //                                                 // Reg Anggota Keluarga
                                                    //                                                 $dtpas['nopasien'] = trim($cekpsserverlama->Nopasien);
                                                    //                                                 $dtpas['idanggotakeluarga'] = $date . rand(1000, 10000);
                                                    //                                                 $dtpas['idakun'] = 'JKN';
                                                    //                                                 $dtpas['hubungan'] = 'Orang Lain';
                                                    //                                                 $dtpas['namalengkap'] = trim($cekpsserverlama->NamaPasien);
                                                    //                                                 $dtpas['namapanggilan'] = trim($cekpsserverlama->NamaPasien);
                                                    //                                                 $dtpas['ktp'] = trim($cekpsserverlama->NoKTP);
                                                    //                                                 $dtpas['jeniskelamin'] = trim($cekpsserverlama->JenisKelamin);
                                                    //                                                 $dtpas['tempatlahir'] = '';
                                                    //                                                 $dtpas['tgllahir'] = trim($cekpsserverlama->TglLahir);
                                                    //                                                 $dtpas['alamat'] = trim($cekpsserverlama->AlamatPasien);
                                                    //                                                 $dtpas['rt'] = '';
                                                    //                                                 $dtpas['rw'] = '';
                                                    //                                                 $dtpas['provinsi'] = '';
                                                    //                                                 $dtpas['kabupaten'] = '';
                                                    //                                                 $dtpas['kecamatan'] = '';
                                                    //                                                 $dtpas['kodepos'] = '';
                                                    //                                                 $dtpas['agama'] = '';
                                                    //                                                 $dtpas['goloangandarah'] = '';
                                                    //                                                 $dtpas['pendidikan'] = '';
                                                    //                                                 $dtpas['statuskawin'] = '';
                                                    //                                                 $dtpas['pekerjaan'] = '';
                                                    //                                                 $dtpas['wni'] = '';
                                                    //                                                 $dtpas['negara'] = '';
                                                    //                                                 $dtpas['suku'] = '';
                                                    //                                                 $dtpas['bahasa'] = '';
                                                    //                                                 $dtpas['alergi'] = '';
                                                    //                                                 $dtpas['alamatkantor'] = '';
                                                    //                                                 $dtpas['telpkantor'] = '';
                                                    //                                                 $dtpas['namakeluarga'] = '';
                                                    //                                                 $dtpas['namaayah'] = '';
                                                    //                                                 $dtpas['namaibu'] = '';
                                                    //                                                 $dtpas['namasuamiistri'] = '';
                                                    //                                                 $dtpas['notelpon'] = trim($cekpsserverlama->TlpPasien);
                                                    //                                                 $dtpas['email'] = '';

                                                    //                                                 $this->mmobilejkn->simpanPasienLama($dtpas);
                                                    //                                             }
                                                    //                                         } else {
                                                    //                                             // Data Pasien
                                                    //                                             if ($cekanggotakeluarga) {
                                                    //                                                 $dtpas['idanggotakeluarga'] = $cekanggotakeluarga->idAnggotaKeluarga;
                                                    //                                             } else {
                                                    //                                                 // Reg Anggota Keluarga
                                                    //                                                 $dtpas['idanggotakeluarga'] = $date . rand(1000, 10000);
                                                    //                                                 $dtpas['idakun'] = 'JKN';
                                                    //                                                 $dtpas['hubungan'] = 'Orang Lain';
                                                    //                                                 $dtpas['namalengkap'] = '';
                                                    //                                                 $dtpas['namapanggilan'] = '';
                                                    //                                                 $dtpas['ktp'] = $dt['nik'];
                                                    //                                                 $dtpas['jeniskelamin'] = '';
                                                    //                                                 $dtpas['tempatlahir'] = '';
                                                    //                                                 $dtpas['tgllahir'] = '';
                                                    //                                                 $dtpas['alamat'] = '';
                                                    //                                                 $dtpas['rt'] = '';
                                                    //                                                 $dtpas['rw'] = '';
                                                    //                                                 $dtpas['provinsi'] = '';
                                                    //                                                 $dtpas['kabupaten'] = '';
                                                    //                                                 $dtpas['kecamatan'] = '';
                                                    //                                                 $dtpas['kodepos'] = '';
                                                    //                                                 $dtpas['agama'] = '';
                                                    //                                                 $dtpas['goloangandarah'] = '';
                                                    //                                                 $dtpas['pendidikan'] = '';
                                                    //                                                 $dtpas['statuskawin'] = '';
                                                    //                                                 $dtpas['pekerjaan'] = '';
                                                    //                                                 $dtpas['wni'] = '';
                                                    //                                                 $dtpas['negara'] = '';
                                                    //                                                 $dtpas['suku'] = '';
                                                    //                                                 $dtpas['bahasa'] = '';
                                                    //                                                 $dtpas['alergi'] = '';
                                                    //                                                 $dtpas['alamatkantor'] = '';
                                                    //                                                 $dtpas['telpkantor'] = '';
                                                    //                                                 $dtpas['namakeluarga'] = '';
                                                    //                                                 $dtpas['namaayah'] = '';
                                                    //                                                 $dtpas['namaibu'] = '';
                                                    //                                                 $dtpas['namasuamiistri'] = '';
                                                    //                                                 $dtpas['notelpon'] = '';
                                                    //                                                 $dtpas['email'] = '';

                                                    //                                                 $this->mmobilejkn->simpanPasienBaru($dtpas);
                                                    //                                             }
                                                    //                                         }
                                                    //                                     }

                                                    //                                     // Data Registrasi
                                                    //                                     $dtreg['idanggotakeluarga'] = $dtpas['idanggotakeluarga'];

                                                    //                                     $dtreg['bagian'] = $cekjampolisore->KodeKlinik;
                                                    //                                     $dtreg['namabagian'] = $cekjampolisore->NamaBagian;

                                                    //                                     $dtreg['penjamin'] = '';
                                                    //                                     $dtreg['namapenjamin'] = '';

                                                    //                                     $dtreg['nopenjamin'] = $dt['nomorkartu'];
                                                    //                                     $dtreg['norujukan'] = $dt['nomorreferensi'];

                                                    //                                     $dtreg['dokter'] = $cekjampolisore->KodeDokter;
                                                    //                                     $dtreg['namadokter'] = $cekjampolisore->NamaDokter;

                                                    //                                     $dtreg['waktu'] = 'P';

                                                    //                                     $hitungbooking = $this->mregbooking->hitungBooking(str_replace("-", "", $dt['tanggalperiksa']));
                                                    //                                     $dtreg['kodebooking'] = str_replace("-", "", $dt['tanggalperiksa']) . str_pad($hitungbooking + 1, 4, "0", STR_PAD_LEFT);
                                                    //                                     $jam = "07:00";
                                                    //                                     $time = strtotime($jam);
                                                    //                                     $datetime = date("Y-m-d H:i", strtotime($dt['tanggalperiksa'] . $jam));

                                                    //                                     if ($getkodepoli->RuangId == "6101") {
                                                    //                                         $hitungpendaftaran = $this->mregbooking->hitungPendaftaranObsgyn(str_replace("-", "", $dt['tanggalperiksa']), $dtreg['waktu']);
                                                    //                                         $hitungpoli = $this->mregbooking->hitungPoli($dtreg['bagian'], str_replace("-", "", $dt['tanggalperiksa']), $dtreg['waktu']);
                                                    //                                         $dtreg['noantripendaftaran'] = "A" . str_pad($hitungpendaftaran + 1, 4, "0", STR_PAD_LEFT);
                                                    //                                         $dtreg['noantripoli'] = str_pad($hitungpoli + 1, 3, "0", STR_PAD_LEFT);

                                                    //                                         $jml = $hitungpendaftaran;
                                                    //                                         $pelayanan = 3;
                                                    //                                         $wkt = $jml * $pelayanan;
                                                    //                                         $jamdilayani = date("H:i", strtotime('+' . $wkt . ' minutes', $time));
                                                    //                                         $dtreg['jamdilayani'] = date("Y-m-d", strtotime($dt['tanggalperiksa'])) . " " . $jamdilayani;
                                                    //                                         $dtreg['datetime'] = $datetime;
                                                    //                                     } else {
                                                    //                                         $hitungpendaftaran = $this->mregbooking->hitungPendaftaranLain(str_replace("-", "", $dt['tanggalperiksa']));
                                                    //                                         $hitungpoli = $this->mregbooking->hitungPoli($getkodepoli->RuangId, str_replace("-", "", $dt['tanggalperiksa']), $dtreg['waktu']);
                                                    //                                         $dtreg['noantripendaftaran'] = "B" . str_pad($hitungpendaftaran + 1, 4, "0", STR_PAD_LEFT);
                                                    //                                         $dtreg['noantripoli'] = str_pad($hitungpoli + 1, 3, "0", STR_PAD_LEFT);

                                                    //                                         $jml = $hitungpendaftaran;
                                                    //                                         $pelayanan = 3;
                                                    //                                         $wkt = $jml * $pelayanan;
                                                    //                                         $jamdilayani = date("H:i", strtotime('+' . $wkt . ' minutes', $time));
                                                    //                                         $dtreg['jamdilayani'] = date("Y-m-d", strtotime($dt['tanggalperiksa'])) . " " . $jamdilayani;
                                                    //                                         $dtreg['datetime'] = $datetime;
                                                    //                                     }

                                                    //                                     if ($this->mregbooking->simpanBooking($dtreg)) {
                                                    //                                         $this->response([
                                                    //                                             'metadata' => [
                                                    //                                                 'code' => 200,
                                                    //                                                 'message' => 'OK'
                                                    //                                             ],
                                                    //                                             'response' => [
                                                    //                                                 'nomorantrean' => trim($dtreg['noantripendaftaran']),
                                                    //                                                 'kodebooking' => trim($dtreg['kodebooking']),
                                                    //                                                 'jenisantrean' => 1,
                                                    //                                                 'estimasidilayani' => strtotime($dtreg['jamdilayani']) * 1000,
                                                    //                                                 'namapoli' => trim($dtreg['namabagian']),
                                                    //                                                 'namadokter' => trim($dtreg['namadokter'])
                                                    //                                             ]
                                                    //                                         ], 200);
                                                    //                                     } else {
                                                    //                                         $this->response([
                                                    //                                             'metadata' => [
                                                    //                                                 'code' => 203,
                                                    //                                                 'message' => 'Gagal booking, silahkan periksa data dan coba lagi'
                                                    //                                             ]
                                                    //                                         ], 203);
                                                    //                                     }
                                                    //                                 }
                                                    //                             } else {
                                                    //                                 $this->response([
                                                    //                                     'metadata' => [
                                                    //                                         'code' => 203,
                                                    //                                         'message' => 'Poli Libur'
                                                    //                                     ]
                                                    //                                 ], 203);
                                                    //                             }
                                                    //                         } else {
                                                    //                             $this->response([
                                                    //                                 'metadata' => [
                                                    //                                     'code' => 203,
                                                    //                                     'message' => 'Poli Libur'
                                                    //                                 ]
                                                    //                             ], 203);
                                                    //                         }
                                                    //                     }
                                                    //                 } else {
                                                    //                     $this->response([
                                                    //                         'metadata' => [
                                                    //                             'code' => 203,
                                                    //                             'message' => 'Poli Libur'
                                                    //                         ]
                                                    //                     ], 203);
                                                    //                 }
                                                    //             } else {
                                                    //                 $this->response([
                                                    //                     'metadata' => [
                                                    //                         'code' => 203,
                                                    //                         'message' => 'Kode Poli Tidak Terdaftar'
                                                    //                     ]
                                                    //                 ], 203);
                                                    //             }
                                                    //         }
                                                    //     } else {
                                                    //         $this->response([
                                                    //             'metadata' => [
                                                    //                 'code' => 203,
                                                    //                 'message' => 'Invalid Nomor Rujukan'
                                                    //             ]
                                                    //         ], 203);
                                                    //     }
                                                    // }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
