<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mpendidikan extends CI_Model
{

    function getPendidikan()
    {
        return $this->db->query("SELECT * FROM MasterLulusan")->result_array();
    }

    function getPendidikanById($id)
    {
        return $this->db->query("SELECT * FROM MasterLulusan WHERE KodeLulusan = '$id'")->row();
    }
}
