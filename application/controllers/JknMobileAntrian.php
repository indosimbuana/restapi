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

        $dtreg = array();

        $dt = array();
        $dt['nomorkartu'] = $data['nomorkartu'];
        $dt['nik'] = $data['nik'];
        $dt['tanggalperiksa'] = $data['tanggalperiksa'];
        $dt['kodepoli'] = $data['kodepoli'];
        $dt['nomorreferensi'] = $data['nomorreferensi'];
        $dt['jenisreferensi'] = $data['jenisreferensi'];
        $dt['jenisrequest'] = $data['jenisrequest'];
        $dt['polieksekutif'] = $data['polieksekutif'];

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

                        if ($getkodepoli->RuangId == "6101") {
                            $hitungpendaftaran = $this->mregbooking->hitungPendaftaranObsgyn(str_replace("-", "", $dt['tanggalperiksa']), $dtreg['waktu']);
                            $hitungpoli = $this->mregbooking->hitungPoli($dtreg['bagian'], str_replace("-", "", $dt['tanggalperiksa']), $dtreg['waktu']);
                            $dtreg['noantripendaftaran'] = "A" . str_pad($hitungpendaftaran + 1, 4, "0", STR_PAD_LEFT);
                            $dtreg['noantripoli'] = str_pad($hitungpoli + 1, 3, "0", STR_PAD_LEFT);

                            $jml = $hitungpendaftaran;
                            $pelayanan = 3;
                            $wkt = $jml * $pelayanan;
                            $jamdilayani = date("H:i", strtotime('+' . $wkt . ' minutes', $time));
                            $dtreg['jamdilayani'] = date("Y-m-d", strtotime($dt['tanggalperiksa'])) . " " . $jamdilayani;
                            $dtreg['datetime'] = $datetime;
                        } else {
                            $hitungpendaftaran = $this->mregbooking->hitungPendaftaranLain(str_replace("-", "", $dt['tanggalperiksa']));
                            $hitungpoli = $this->mregbooking->hitungPoli($getkodepoli->RuangId, str_replace("-", "", $dt['tanggalperiksa']), $dtreg['waktu']);
                            $dtreg['noantripendaftaran'] = "B" . str_pad($hitungpendaftaran + 1, 4, "0", STR_PAD_LEFT);
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

                                if ($getkodepoli->RuangId == "6101") {
                                    $hitungpendaftaran = $this->mregbooking->hitungPendaftaranObsgyn(str_replace("-", "", $dt['tanggalperiksa']), $dtreg['waktu']);
                                    $hitungpoli = $this->mregbooking->hitungPoli($dtreg['bagian'], str_replace("-", "", $dt['tanggalperiksa']), $dtreg['waktu']);
                                    $dtreg['noantripendaftaran'] = "A" . str_pad($hitungpendaftaran + 1, 4, "0", STR_PAD_LEFT);
                                    $dtreg['noantripoli'] = str_pad($hitungpoli + 1, 3, "0", STR_PAD_LEFT);

                                    $jml = $hitungpendaftaran;
                                    $pelayanan = 3;
                                    $wkt = $jml * $pelayanan;
                                    $jamdilayani = date("H:i", strtotime('+' . $wkt . ' minutes', $time));
                                    $dtreg['jamdilayani'] = date("Y-m-d", strtotime($dt['tanggalperiksa'])) . " " . $jamdilayani;
                                    $dtreg['datetime'] = $datetime;
                                } else {
                                    $hitungpendaftaran = $this->mregbooking->hitungPendaftaranLain(str_replace("-", "", $dt['tanggalperiksa']));
                                    $hitungpoli = $this->mregbooking->hitungPoli($getkodepoli->RuangId, str_replace("-", "", $dt['tanggalperiksa']), $dtreg['waktu']);
                                    $dtreg['noantripendaftaran'] = "B" . str_pad($hitungpendaftaran + 1, 4, "0", STR_PAD_LEFT);
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
    }
}
