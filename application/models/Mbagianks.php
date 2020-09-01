<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mbagianks extends CI_Model
{

    function getBagianKS()
    {
        return $this->db->query("SELECT * FROM RegBagianKS")->result_array();
    }

    function getBagianKSById($id)
    {
        return $this->db->query("SELECT * FROM RegBagianKS WHERE KodeBagian = '$id'")->row();
    }
}
