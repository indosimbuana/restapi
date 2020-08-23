<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mpekerjaan extends CI_Model
{

    function getPekerjaan()
    {
        return $this->db->query("SELECT * FROM MasterPekerjaan ")->result_array();
    }

    function getPekerjaanById($id)
    {
        return $this->db->query("SELECT * FROM MasterPekerjaan WHERE KodePekerjaan = '$id'")->row();
    }
}
