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
        return $this->db->query("SELECT * FROM RegAkun WHERE NamaAkun = '$id'")->row();
    }

    function getAkunByTelp($id)
    {
        return $this->db->query("SELECT * FROM RegAkun WHERE NoTelpon = '$id'")->row();
    }

    function getAkunByEmail($id)
    {
        return $this->db->query("SELECT * FROM RegAkun WHERE Email = '$id'")->row();
    }

    function regAkun($data)
    {
        return $this->db->query("INSERT INTO RegAkun (NamaAkun, Email, NoTelpon, Password, LevelAkun)
        VALUES (" . $this->db->escape($data['nama']) . "," . $this->db->escape($data['email']) . "," . $this->db->escape($data['telp']) . "," . $this->db->escape($data['password']) . "," . $this->db->escape($data['level']) . ");");
    }
}
