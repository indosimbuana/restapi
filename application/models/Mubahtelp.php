<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mubahtelp extends CI_Model
{

    function cekAkun($dt)
    {
        return $this->db->query("SELECT * FROM RegAkun WHERE (NamaAkun = '" . $dt['user'] . "' OR Email = '" . $dt['user'] . "' OR NoTelpon = '" . $dt['user'] . "') AND password = '" . $dt['password'] . "'")->row();
    }

    function ubahTelp($dt)
    {
        return $this->db->query("UPDATE RegAkun SET NoTelpon = " . $this->db->escape($dt['notelp']) . "  WHERE (NamaAkun = '" . $dt['user'] . "' OR Email = '" . $dt['user'] . "' OR NoTelpon = '" . $dt['user'] . "') ");
    }
}
