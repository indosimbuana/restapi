<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mdokter extends CI_Model
{

    function getDokter()
    {
        return $this->db->query("SELECT * FROM MasterDokter WHERE StatusAktif = 'A'")->result_array();
    }

    function getDokterById($id)
    {
        return $this->db->query("SELECT * FROM MasterDokter WHERE KodeDokter = '$id' AND StatusAktif = 'A'")->row();
    }
}
