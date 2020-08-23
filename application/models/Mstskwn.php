<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mstskwn extends CI_Model
{

    function getStsKwn()
    {
        return $this->db->query("SELECT * FROM MasterStatusNikah")->result_array();
    }

    function getStsKwnById($id)
    {
        return $this->db->query("SELECT * FROM MasterStatusNikah WHERE KodeStatus = '$id'")->row();
    }
}
