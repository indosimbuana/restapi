<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mubahemail extends CI_Model
{

    function cekAkun($dt)
    {
        return $this->db->query("SELECT * FROM RegAkun WHERE (NamaAkun = '" . $dt['user'] . "' OR Email = '" . $dt['user'] . "' OR NoTelpon = '" . $dt['user'] . "') AND password = '" . $dt['password'] . "'")->row();
    }

    function ubahEmail($dt)
    {
        return $this->db->query("UPDATE RegAkun SET Email = " . $this->db->escape($dt['email']) . "  WHERE (NamaAkun = '" . $dt['user'] . "' OR Email = '" . $dt['user'] . "' OR NoTelpon = '" . $dt['user'] . "') ");
    }
}
