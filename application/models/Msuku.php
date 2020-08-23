<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Msuku extends CI_Model
{

    function getSuku()
    {
        return $this->db->query("SELECT * FROM MasterSuku WHERE StsAktif = 'Y'")->result_array();
    }

    function getSukuById($id)
    {
        return $this->db->query("SELECT * FROM MasterSuku WHERE KodeSuku = '$id' AND StsAktif = 'Y'")->row();
    }
}
