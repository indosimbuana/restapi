<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mkelurahan extends CI_Model
{

    function getKelurahan()
    {
        return $this->db->query("SELECT * FROM MasterKelurahan")->result_array();
    }

    function getKelurahanById($id)
    {
        return $this->db->query("SELECT * FROM MasterKelurahan WHERE KodeKel = '$id'")->row();
    }

    function getKelurahanByKec($id)
    {
        return $this->db->query("SELECT * FROM MasterKelurahan WHERE KodeKec = '$id'")->result_array();
    }
}
