<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mcekpasienserverlama extends CI_Model
{

    function cekPasien($dt)
    {
        $db2 = $this->load->database('serverlama', TRUE);
        $sql = $db2->query("SELECT NORM AS Nopasien, NAMA AS NamaPasien, NamaPanggilan AS NamaPanggilan, TEMPATLAHIR AS TempatLahir, TGLLAHIR AS TglLahir, JNSKELAMIN AS JenisKelamin, ALAMAT AS AlamatPasien, RT AS RT, RW AS RW, NEGARA AS Negara, KODEPOS AS KodePos, NOTELP AS TlpPasien, NamaPasangan AS NamaSuamiIstri, NOKTP AS NoKTP 
        FROM tbpasien 
        WHERE NORM = '" . $dt['nopasien'] . "' AND TGLLAHIR = '" . $dt['tgllahir'] . "'");
        return $sql->row();
    }

    function getPasien($no)
    {
        $db2 = $this->load->database('serverlama', TRUE);
        $sql = $db2->query("SELECT NORM AS Nopasien, NAMA AS NamaPasien, NamaPanggilan AS NamaPanggilan, TEMPATLAHIR AS TempatLahir, TGLLAHIR AS TglLahir, JNSKELAMIN AS JenisKelamin, ALAMAT AS AlamatPasien, RT AS RT, RW AS RW, NEGARA AS Negara, KODEPOS AS KodePos, NOTELP AS TlpPasien, NamaPasangan AS NamaSuamiIstri, NOKTP AS NoKTP 
        FROM tbpasien 
        WHERE NORM = '$no'");
        return $sql->row();
    }
}
