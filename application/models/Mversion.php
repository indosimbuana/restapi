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
        return $this->db->query("UPDATE Syspar SET 
        AndroidVersion = '" . $dt['AndroidVersion'] . "'
        ,UrlPlayStore = '" . $dt['UrlPlayStore'] . "'
        WHERE RegID = '1' ");
    }
}
