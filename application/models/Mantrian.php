<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mantrian extends CI_Model
{

    function pendaftaran($tgl)
    {
        $db2 = $this->load->database('antrian', TRUE);
        $sql = $db2->query("SELECT MAX(NoAntri) as noantri, Tanggal as tanggal, Panggil as panggil FROM tbnoantri WHERE DATE(Tanggal) = '$tgl' AND Panggil = 1 GROUP BY LEFT(NoAntri,1)");
        return $sql->result_array();
    }

    function poli($tgl)
    {
        $db2 = $this->load->database('antrian', TRUE);
        $sql = $db2->query("SELECT MAX(NO) as noantri, Klinik as klinik, DOKTER as dokter, Tanggal as tanggal FROM tbantrianpoli WHERE DATE(Tanggal) = '$tgl' AND Masuk = 1 GROUP BY Klinik");
        return $sql->result_array();
    }
}
