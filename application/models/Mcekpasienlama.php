<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mcekpasienlama extends CI_Model
{

    function cekPasien($dt)
    {
        return $this->db->query("SELECT * FROM MasterPasien WHERE Nopasien = '" . $dt['nopasien'] . "' AND TglLahir = '" . $dt['tgllahir'] . "'")->row();
    }

    function getPasien($no)
    {
        return $this->db->query("SELECT * FROM MasterPasien WHERE Nopasien = '" . $no . "'")->row();
    }
}
