<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mpenjamin extends CI_Model
{

    function getPenjamin()
    {
        return $this->db->query("SELECT KodePT, Nama FROM MasterPenjamin WHERE Aktif = 'y'")->result_array();
    }

    function getPenjaminById($id)
    {
        return $this->db->query("SELECT KodePT, Nama FROM MasterPenjamin WHERE KodePT = '$id' AND Aktif = 'y'")->row();
    }
}
