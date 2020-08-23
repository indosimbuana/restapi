<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mgoldarah extends CI_Model
{

    function getGolDarah()
    {
        return $this->db->query("SELECT * FROM MasterGolDarah")->result_array();
    }

    function getGolDarahById($id)
    {
        return $this->db->query("SELECT * FROM MasterGolDarah WHERE KodeGolDarah = '$id'")->row();
    }
}
