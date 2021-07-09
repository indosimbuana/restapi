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

    // function poli($tgl)
    // {
    //     $db2 = $this->load->database('antrian', TRUE);
    //     $sql = $db2->query("SELECT MAX(NO) as noantri, Klinik as klinik, DOKTER as dokter, Tanggal as tanggal FROM tbantrianpoli WHERE DATE(Tanggal) = '$tgl' AND Masuk = 1 GROUP BY Klinik");
    //     return $sql->result_array();
    // }

    function poli($tgl)
    {
        $db2 = $this->load->database('antrian', TRUE);
        $sql = $db2->query("SELECT a.NO as noantri, a.Klinik as klinik, a.DOKTER as dokter, a.Nama as nama, a.Alamat as alamat, a.No_Pasien as nopasien, a.Tanggal as tanggal
        FROM tbantrianpoli a
        INNER JOIN 
        (
        SELECT MAX(NO) AS Nomor, Klinik, DOKTER, Nama, Alamat, No_Pasien, Tanggal, Masuk
        FROM tbantrianpoli 
        WHERE DATE(Tanggal) = '$tgl' AND Masuk = 1
        GROUP BY Klinik
        ) b
        ON a.NO = b.Nomor AND a.Klinik = b.Klinik AND DATE(a.Tanggal) = DATE(b.Tanggal) AND a.Masuk = b.Masuk
        ORDER BY a.Klinik");
        return $sql->result_array();
    }

    function semuapoli($tgl)
    {
        $db2 = $this->load->database('antrian', TRUE);
        $sql = $db2->query("SELECT Klinik as klinik, COUNT(*) AS total, SUM(IF(Masuk = '1',1,0)) AS jumlahmasuk  FROM tbantrianpoli WHERE DATE(Tanggal) = '$tgl' GROUP BY Klinik");
        return $sql->result_array();
    }

    function daftarpasienpoli($poli, $tgl)
    {
        $db2 = $this->load->database('antrian', TRUE);
        $sql = $db2->query("SELECT NO as noantri, Klinik as klinik, DOKTER as dokter, No_Pasien as nopasien, Nama as nama, Alamat as alamat, Tanggal as tanggal, Masuk as masuk FROM tbantrianpoli WHERE DATE(Tanggal) = '$tgl' AND Klinik = '$poli'");
        return $sql->result_array();
    }

    function poliaktif($tgl)
    {
        $db2 = $this->load->database('antrian', TRUE);
        $sql = $db2->query("SELECT NO as noantri, Klinik as klinik, DOKTER as dokter, No_Pasien as nopasien, Nama as nama, Alamat as alamat, Tanggal as tanggal, Masuk as stsmasuk FROM tbantrianpoli WHERE DATE(Tanggal) = '$tgl' GROUP BY Klinik");
        return $sql->result_array();
    }
}
