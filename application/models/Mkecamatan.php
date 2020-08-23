<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mkecamatan extends CI_Model
{

    function getKecamatan()
    {
        return $this->db->query("SELECT KODEPROV, KODEKOTA, KODEKEC, NAMAKEC FROM MasterKecamatan WHERE NAMAKEC != '' AND NAMAKEC != '-' AND NAMAKEC IS NOT NULL ORDER BY KODEPROV")->result_array();
    }

    function getKecamatanByKab($prov, $kab)
    {
        return $this->db->query("SELECT KODEKEC, NAMAKEC FROM MasterKecamatan WHERE KODEPROV = '$prov' AND KODEKOTA = '$kab' AND NAMAKEC != '' AND NAMAKEC != '-' AND NAMAKEC IS NOT NULL")->result_array();
    }
}
