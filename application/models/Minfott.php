<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Minfott extends CI_Model
{

    function getInfoTT()
    {
        return $this->db->query("SELECT mk.KodeRuang, mi.NamaBagian, mk.Kelas, mc.NamaKelas, COUNT(mk.NOTT) AS JumlahTT, SUM(CASE WHEN mk.isi IS NULL OR mk.isi = '0' THEN 1 ELSE 0 END) AS JumlahTTKosong FROM MasterKamarTT mk
        LEFT JOIN MasterInstalasi mi ON mi.KodeBagian = mk.KodeRuang
        LEFT JOIN MasterKelas mc ON mc.KodeKelas = mk.Kelas
        GROUP BY mk.KodeRuang, mi.NamaBagian, mk.Kelas, mc.NamaKelas")->result_array();
    }
}
