<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mregakun extends CI_Model
{

    function getAkun()
    {
        return $this->db->query("SELECT * FROM RegAkun")->result_array();
    }

    function getAkunById($id)
    {
        return $this->db->query("SELECT * FROM RegAkun WHERE NamaAkun = '$id'")->result();
    }

    function regAkun($data)
    {
    }
}
