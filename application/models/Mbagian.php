<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mbagian extends CI_Model
{

    function getBagian()
    {
        return $this->db->query("SELECT * FROM BAGIAN")->result_array();
    }

    function getBagianById($id)
    {
        return $this->db->query("SELECT * FROM BAGIAN WHERE KodeBagian = '$id'")->result();
    }
}
