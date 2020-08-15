<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mresetpassword extends CI_Model
{

    function cekKode($dt)
    {
        return $this->db->query("SELECT * FROM ResetPassword WHERE NoTelpon = '" . $dt['telp'] . "' AND KodeReset = '" . $dt['kode'] . "' AND StatusReset = 0")->row();
    }

    function ubahPassword($dt)
    {
        return $this->db->query("UPDATE RegAkun SET Password = " . $this->db->escape($dt['passwordbaru']) . "  WHERE NoTelpon = '" . $dt['telp'] . "' ");
    }

    function ubahReset($dt)
    {
        return $this->db->query("UPDATE ResetPassword SET StatusReset = 1 WHERE NoTelpon = '" . $dt['telp'] . "' ");
    }
}
