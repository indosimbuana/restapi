<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mkritiksaranpj extends CI_Model
{
    function getKritSar($pj)
    {
        return $this->db->query("SELECT * FROM KritikSaran ks
        LEFT JOIN RegBagianKS rk ON rk.KodeBagian = ks.kodeBagian
        LEFT JOIN RegBagianPgjwb rp ON rp.KodeBagian = ks.kodeBagian
        WHERE rp.NamaAkun = '$pj'
        ORDER BY ks.status, ks.tglTanya DESC")->result_array();
    }

    function getKritSarByTgl($pj, $tgl)
    {
        return $this->db->query("SELECT * FROM KritikSaran ks
        LEFT JOIN RegBagianKS rk ON rk.KodeBagian = ks.kodeBagian
        LEFT JOIN RegBagianPgjwb rp ON rp.KodeBagian = ks.kodeBagian
        WHERE rp.NamaAkun = '$pj' AND DATEDIFF(DAY, ks.tglTanya, '$tgl') = 0
        ORDER BY ks.status, ks.tglTanya DESC")->result_array();
    }

    function getKritSarByRangeTgl($pj, $awal, $akhir)
    {
        return $this->db->query("SELECT * FROM KritikSaran ks
        LEFT JOIN RegBagianKS rk ON rk.KodeBagian = ks.kodeBagian
        LEFT JOIN RegBagianPgjwb rp ON rp.KodeBagian = ks.kodeBagian
        WHERE rp.NamaAkun = '$pj' AND (ks.tglTanya BETWEEN '$awal' AND DATEADD(HOUR, 24,'$akhir'))
        ORDER BY ks.status, ks.tglTanya DESC")->result_array();
    }
}
