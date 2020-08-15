<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mlupa extends CI_Model
{

    function cekAkun($dt)
    {
        return $this->db->query("SELECT * FROM RegAkun WHERE (NamaAkun = '" . $dt['user'] . "' OR Email = '" . $dt['user'] . "' OR NoTelpon = '" . $dt['user'] . "') ")->row();
    }

    function simpanKode($data)
    {
        return $this->db->query("INSERT INTO ResetPassword (KodeReset, NoTelpon)
        VALUES (" . $this->db->escape($data['kode']) . "," . $this->db->escape($data['telp']) . ");");
    }
}
