<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mjadwalpoli extends CI_Model
{

    function getPoli()
    {
        return $this->db->query("SELECT jk.*, mi.NamaBagian, md.Nama
        FROM RegJadwalKlinik jk 
        LEFT JOIN MasterInstalasi mi ON mi.KodeBagian = jk.KodeKlinik
        LEFT JOIN MasterDokter md ON md.idDokter = jk.KodeDokter
        WHERE jk.StsAktif = 'Y' ORDER BY mi.NamaBagian DESC")->result_array();
    }

    function getPoliById($id)
    {
        return $this->db->query("SELECT jk.*, mi.NamaBagian, md.Nama
        FROM RegJadwalKlinik jk 
        LEFT JOIN MasterInstalasi mi ON mi.KodeBagian = jk.KodeKlinik
        LEFT JOIN MasterDokter md ON md.idDokter = jk.KodeDokter
        WHERE jk.StsAktif = 'Y' AND jk.KodeKlinik = '$id' ORDER BY mi.NamaBagian DESC")->result_array();
    }
}
