<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mversion extends CI_Model
{

    function getVersion()
    {
        return $this->db->query("SELECT AndroidVersion, UrlPlayStore FROM Syspar")->row();
    }

    function updateVersion($dt)
    {
        $this->db->where('RegID', '1');
        $this->db->update('Syspar', $dt);

        return $this->db->affected_rows() > 0;
    }
}
