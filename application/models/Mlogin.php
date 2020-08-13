<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mlogin extends CI_Model
{

    function cekAkun($dt)
    {
        return $this->db->query("SELECT * FROM RegAkun WHERE (NamaAkun = '".$dt['user']."' OR Email = '".$dt['user']."' OR NoTelpon = '".$dt['user']."') AND password = '".$dt['password']."'")->result();
    }
}
