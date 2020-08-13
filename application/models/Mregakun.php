<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mregakun extends CI_Model
{

    function getAkun()
    {
        return $this->db->query("SELECT * FROM RegAkun")->result_array();
    }

    function getAkunByNama($id)
    {
        return $this->db->query("SELECT * FROM RegAkun WHERE NamaAkun = '$id'")->result();
    }

    function getAkunByTelp($id)
    {
        return $this->db->query("SELECT * FROM RegAkun WHERE NoTelpon = '$id'")->result();
    }

    function getAkunByEmail($id)
    {
        return $this->db->query("SELECT * FROM RegAkun WHERE Email = '$id'")->result();
    }

    function regAkun($data)
    {
        return $this->db->query("INSERT INTO RegAkun (NamaAkun, Email, NoTelpon, Password)
        VALUES (".$this->db->escape($data['nama']).",".$this->db->escape($data['email']).",".$this->db->escape($data['telp']).",".$this->db->escape($data['password']).");");
    }
}
