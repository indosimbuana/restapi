<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mregbooking extends CI_Model
{

    function cekBooking($id, $bag, $tgl, $wkt)
    {
        return $this->db->query("SELECT * FROM RegOnline WHERE idAnggotaKeluarga = '$id' AND kodeBagian = '$bag' AND LEFT(kodeBooking,8) = '$tgl' AND waktuPemeriksaan = '$wkt'")->row();
    }

    function hitungBooking($tgl)
    {
        return $this->db->query("SELECT * FROM RegOnline WHERE LEFT(kodeBooking,8) = '$tgl'")->num_rows();
    }

    function hitungPendaftaranLain($tgl)
    {
        return $this->db->query("SELECT * FROM RegOnline WHERE kodeBagian != '6101' AND LEFT(kodeBooking,8) = '$tgl'")->num_rows();
    }

    function hitungPendaftaranObsgyn($tgl, $wkt)
    {
        return $this->db->query("SELECT * FROM RegOnline WHERE kodeBagian = '6101' AND LEFT(kodeBooking,8) = '$tgl' AND waktuPemeriksaan = '$wkt'")->num_rows();
    }

    function hitungPoli($bag, $tgl, $wkt)
    {
        return $this->db->query("SELECT * FROM RegOnline WHERE kodeBagian = '$bag' AND LEFT(kodeBooking,8) = '$tgl' AND waktuPemeriksaan = '$wkt'")->num_rows();
    }

    function getBagian($id)
    {
        return $this->db->query("SELECT * FROM MasterInstalasi WHERE KodeBagian = '$id'")->row();
    }

    function getPenjamin($id)
    {
        return $this->db->query("SELECT * FROM MasterPenjamin WHERE idPenjamin = '$id'")->row();
    }

    function getDokter($id)
    {
        return $this->db->query("SELECT * FROM MasterDokter WHERE idDokter = '$id'")->row();
    }

    function getJamPoli($bag, $wkt, $dr, $hari)
    {
        return $this->db->query("SELECT $hari FROM RegJadwalKlinik WHERE KodeKlinik = '$bag' AND JenisWaktu = '$wkt' AND KodeDokter = '$dr'")->row();
    }

    function simpanBooking($data)
    {
        return $this->db->query("INSERT INTO RegOnline (
            kodeBooking
            ,idAnggotaKeluarga
            ,noAntrianPendaftaran
            ,noAntrianKlinik
            ,jamDilayani
            ,kodeBagian
            ,namaBagian
            ,penjamin
            ,namaPenjamin
            ,noPenjamin
            ,noRujukan
            ,kodeDokter
            ,namaDokter
            ,tglPemeriksaan
            ,waktuPemeriksaan)
        VALUES (
        " . $this->db->escape($data['kodebooking']) . "
        ," . $this->db->escape($data['idanggotakeluarga']) . "
        ," . $this->db->escape($data['noantripendaftaran']) . "
        ," . $this->db->escape($data['noantripoli']) . "
        ," . $this->db->escape($data['jamdilayani']) . "
        ," . $this->db->escape($data['bagian']) . "
        ," . $this->db->escape($data['namabagian']) . "
        ," . $this->db->escape($data['penjamin']) . "
        ," . $this->db->escape($data['namapenjamin']) . "
        ," . $this->db->escape($data['nopenjamin']) . "
        ," . $this->db->escape($data['norujukan']) . "
        ," . $this->db->escape($data['dokter']) . "
        ," . $this->db->escape($data['namadokter']) . "
        ," . $this->db->escape($data['datetime']) . "
        ," . $this->db->escape($data['waktu']) . ");");
    }
}
