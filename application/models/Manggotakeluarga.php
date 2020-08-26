<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Manggotakeluarga extends CI_Model
{

    function getAnggotaAkun($akun)
    {
        return $this->db->query("SELECT * FROM RegAnggotaKeluarga WHERE idAkun = '$akun'")->result_array();
    }

    function getAnggotaById($id)
    {
        return $this->db->query("SELECT * FROM RegAnggotaKeluarga WHERE idAnggotaKeluarga = '$id'")->row();
    }
}
