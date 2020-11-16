<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mversion extends CI_Model
{

    function getVersion()
    {
        return $this->db->query("SELECT AndroidVersion FROM Syspar")->row();
    }

    function updateVersion($dt)
    {
        return $this->db->query("UPDATE Syspar SET 
        AndroidVersion = '" . $dt['AndroidVersion'] . "'
        WHERE RegID = '1' ");
    }
}
