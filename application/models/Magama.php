<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Magama extends CI_Model
{

    function getAgama()
    {
        return $this->db->query("SELECT * FROM MasterAgama WHERE Aktif = 'Y'")->result_array();
    }

    function getAgamaById($id)
    {
        return $this->db->query("SELECT * FROM MasterAgama WHERE KodeAgama = '$id' AND Aktif = 'Y'")->row();
    }
}
