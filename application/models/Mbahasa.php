<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mbahasa extends CI_Model
{

    function getBahasa()
    {
        return $this->db->query("SELECT * FROM MasterBahasa")->result_array();
    }

    function getBahasaById($id)
    {
        return $this->db->query("SELECT * FROM MasterBahasa WHERE KodeBahasa = '$id'")->row();
    }
}
