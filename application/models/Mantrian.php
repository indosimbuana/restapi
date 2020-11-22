<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mantrian extends CI_Model
{

    function pendaftaran()
    {
        $db2 = $this->load->database('antrian', TRUE);
        $sql = $db2->query("SELECT * FROM tbantrian");
        return $sql->result_array();
    }

    function poli($poli, $tgl)
    {
        $db2 = $this->load->database('antrian', TRUE);
        $sql = $db2->query("SELECT * FROM tbantrianpoli WHERE Klinik = '$poli' AND DATE(Tanggal) = '$tgl' AND Masuk = 0 AND Lewati = 0 ORDER BY NO LIMIT 1");
        return $sql->row();
    }

    function totalBelumMasuk($poli, $tgl)
    {
        $db2 = $this->load->database('antrian', TRUE);
        $sql = $db2->query("SELECT COUNT(NO) AS Jumlah FROM tbantrianpoli WHERE Klinik = '$poli' AND DATE(Tanggal) = '$tgl' AND Masuk = 0 AND Lewati = 0");
        return $sql->row();
    }

    function totalSudahMasuk($poli, $tgl)
    {
        $db2 = $this->load->database('antrian', TRUE);
        $sql = $db2->query("SELECT COUNT(NO) AS Jumlah FROM tbantrianpoli WHERE Klinik = '$poli' AND DATE(Tanggal) = '$tgl' AND Masuk = 1 AND Lewati = 0");
        return $sql->row();
    }

    function totalDilewati($poli, $tgl)
    {
        $db2 = $this->load->database('antrian', TRUE);
        $sql = $db2->query("SELECT COUNT(NO) AS Jumlah FROM tbantrianpoli WHERE Klinik = '$poli' AND DATE(Tanggal) = '$tgl' AND Masuk = 1 AND Lewati = 1");
        return $sql->row();
    }
}
