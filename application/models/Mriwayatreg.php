<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mriwayatreg extends CI_Model
{

    function getRiwayatReg($id)
    {
        return $this->db->query("SELECT * FROM RegOnline WHERE idAnggotaKeluarga = '$id'")->result_array();
    }

    function getRiwayatById($id)
    {
        return $this->db->query("SELECT * FROM RegOnline WHERE kodeBooking = '$id'")->row();
    }

    function getJamPoli($bag, $wkt, $dr, $hari)
    {
        return $this->db->query("SELECT $hari FROM RegJadwalKlinik WHERE KodeKlinik = '$bag' AND JenisWaktu = '$wkt' AND KodeDokter = '$dr'")->row();
    }
}
