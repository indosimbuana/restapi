<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mprovinsi extends CI_Model
{

    function getProvinsi()
    {
        return $this->db->query("SELECT KODEPROV, PROVINCE FROM MasterProvinsi")->result_array();
    }

    function getProvinsiById($id)
    {
        return $this->db->query("SELECT KODEPROV, PROVINCE FROM MasterProvinsi WHERE KODEPROV = '$id'")->row();
    }
}
