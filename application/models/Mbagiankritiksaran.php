<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mbagiankritiksaran extends CI_Model
{

    function getBagian()
    {
        return $this->db->query("SELECT * FROM RegBagianKS")->result_array();
    }

    function getBagianByUser($id)
    {
        return $this->db->query("SELECT rk.* FROM RegBagianKS rk
        LEFT JOIN RegBagianPgjwb rp ON rp.KodeBagian = rk.KodeBagian
        WHERE rp.NamaAkun = '$id'")->result_array();
    }
}
