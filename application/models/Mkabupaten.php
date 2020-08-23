<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mkabupaten extends CI_Model
{

    function getKabupaten()
    {
        return $this->db->query("SELECT KODEPROV, KODEKOTA, NAMAKOTA FROM MasterKabupaten WHERE NAMAKOTA != '' AND NAMAKOTA != '-' AND NAMAKOTA IS NOT NULL ORDER BY KODEPROV")->result_array();
    }

    function getKabupatenByProv($id)
    {
        return $this->db->query("SELECT KODEKOTA, NAMAKOTA FROM MasterKabupaten WHERE KODEPROV = '$id' AND NAMAKOTA != '' AND NAMAKOTA != '-' AND NAMAKOTA IS NOT NULL")->result_array();
    }
}
