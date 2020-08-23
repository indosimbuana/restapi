<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mlevelakun extends CI_Model
{

    function getLevel()
    {
        return $this->db->query("SELECT * FROM RegLevelAkun")->result_array();
    }

    function getLevelById($id)
    {
        return $this->db->query("SELECT * FROM RegLevelAkun WHERE IDLevel = '$id'")->row();
    }
}
