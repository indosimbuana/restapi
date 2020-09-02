<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mpoli extends CI_Model
{

    function getPoli()
    {
        return $this->db->query("SELECT * FROM MasterInstalasi WHERE GrpUnit = '61' AND StsAktif = 'Y' AND LEFT(KodeBagian, 2) = '61'")->result_array();
    }

    function getPoliById($id)
    {
        return $this->db->query("SELECT * FROM MasterInstalasi WHERE KodeBagian = '$id' AND StsAktif = 'Y'")->row();
    }
}
