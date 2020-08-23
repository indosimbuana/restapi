<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mpilihpoli extends CI_Model
{

    function getJadwalPoli($jns)
    {
        return $this->db->query("SELECT jk.*, mi.NamaBagian, md.idDokter, md.Nama FROM RegJadwalKlinik jk 
        LEFT JOIN MasterDokter md ON md.idDokter = jk.KodeDokter
        LEFT JOIN MasterInstalasi mi ON mi.KodeBagian = jk.KodeKlinik
        WHERE jk.StsAktif = 'Y' AND JenisWaktu = '$jns'")->result_array();
    }

    function getDokterLibur($tgl, $dr)
    {
        return $this->db->query("SELECT * FROM RegDokterLibur WHERE TglLibur = '$tgl' AND KodeDokter = '$dr'")->row();
    }

    function getKlinikLibur($tgl, $kl)
    {
        return $this->db->query("SELECT * FROM RegKlinikLibur WHERE TglLibur = '$tgl' AND KodeKlinik = '$kl'")->row();
    }
}
