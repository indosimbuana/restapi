<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mpilihtanggal extends CI_Model
{

    function getHariLibur()
    {
        return $this->db->query("SELECT * FROM RegHariLibur")->result_array();
    }

    function getHariLiburByTahun($th)
    {
        return $this->db->query("SELECT * FROM RegHariLibur WHERE Tahun = '$th'")->result_array();
    }

    function getHariLiburById($id)
    {
        return $this->db->query("SELECT * FROM RegHariLibur WHERE IDhariLibur = '$id'")->result_array();
    }

    function getHariLiburByTanggal($tgl)
    {
        return $this->db->query("SELECT * FROM RegHariLibur WHERE TglLibur = '$tgl'")->result_array();
    }
}
