<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mjadwalpoli extends CI_Model
{

    function getPoli()
    {
        return $this->db->query("SELECT jk.*, mi.NamaBagian, md.NamaDokter
        FROM RegJadwalKlinik jk 
        LEFT JOIN MasterInstalasi mi ON mi.KodeBagian = jk.KodeKlinik
        LEFT JOIN MasterDokter md ON md.KodeDokter = jk.KodeDokter
        WHERE jk.StsAktif = 'Y' ORDER BY mi.NamaBagian DESC")->result_array();
    }

    function getPoliById($id)
    {
        return $this->db->query("SELECT jk.*, mi.NamaBagian, md.NamaDokter
        FROM RegJadwalKlinik jk 
        LEFT JOIN MasterInstalasi mi ON mi.KodeBagian = jk.KodeKlinik
        LEFT JOIN MasterDokter md ON md.KodeDokter = jk.KodeDokter
        WHERE jk.StsAktif = 'Y' AND jk.KodeKlinik = '$id' ORDER BY mi.NamaBagian DESC, jk.JenisWaktu ASC")->result_array();
    }

    function cekJadwalPoli($data)
    {
        return $this->db->query("SELECT * FROM RegJadwalKlinik WHERE KodeKlinik = '" . $data['KodeKlinik'] . "' AND JenisWaktu = '" . $data['JenisWaktu'] . "' AND KodeDokter = '" . $data['KodeDokter'] . "'")->row();
    }

    function simpanJadwalPoli($data)
    {
        return $this->db->query("INSERT INTO RegJadwalKlinik (
            KodeKlinik
            ,JenisWaktu
            ,KodeDokter
            ,Senin
            ,SeninTutup
            ,Selasa
            ,SelasaTutup
            ,Rabu
            ,RabuTutup
            ,Kamis
            ,KamisTutup
            ,Jumat
            ,JumatTutup
            ,Sabtu
            ,SabtuTutup
            ,Minggu
            ,MingguTutup
            ,JamBukaPendaftaran
            ,JamTutupPendaftaran
            ,Keterangan
            ,UserUpdate
            ,UpdateTime
            )
            VALUES (
            " . $this->db->escape($data['KodeKlinik']) . "
            ," . $this->db->escape($data['JenisWaktu']) . "
            ," . $this->db->escape($data['KodeDokter']) . "
            ," . $this->db->escape($data['Senin']) . "
            ," . $this->db->escape($data['SeninTutup']) . "
            ," . $this->db->escape($data['Selasa']) . "
            ," . $this->db->escape($data['SelasaTutup']) . "
            ," . $this->db->escape($data['Rabu']) . "
            ," . $this->db->escape($data['RabuTutup']) . "
            ," . $this->db->escape($data['Kamis']) . "
            ," . $this->db->escape($data['KamisTutup']) . "
            ," . $this->db->escape($data['Jumat']) . "
            ," . $this->db->escape($data['JumatTutup']) . "
            ," . $this->db->escape($data['Sabtu']) . "
            ," . $this->db->escape($data['SabtuTutup']) . "
            ," . $this->db->escape($data['Minggu']) . "
            ," . $this->db->escape($data['MingguTutup']) . "
            ," . $this->db->escape($data['JamBukaPendaftaran']) . "
            ," . $this->db->escape($data['JamTutupPendaftaran']) . "
            ," . $this->db->escape($data['Keterangan']) . "
            ," . $this->db->escape($data['UserUpdate']) . "
            ," . $this->db->escape($data['UpdateTime']) . "
        );");
    }

    function updateJadwalPoli($dt)
    {
        return $this->db->query("UPDATE RegJadwalKlinik SET 
        Senin = '" . $dt['Senin'] . "'
        ,SeninTutup = '" . $dt['SeninTutup'] . "'
        ,Selasa = '" . $dt['Selasa'] . "' 
        ,SelasaTutup = '" . $dt['SelasaTutup'] . "'
        ,Rabu = '" . $dt['Rabu'] . "'
        ,RabuTutup = '" . $dt['RabuTutup'] . "'
        ,Kamis = '" . $dt['Kamis'] . "'
        ,KamisTutup = '" . $dt['KamisTutup'] . "'
        ,Jumat = '" . $dt['Jumat'] . "'
        ,JumatTutup = '" . $dt['JumatTutup'] . "'
        ,Sabtu = '" . $dt['Sabtu'] . "'
        ,SabtuTutup = '" . $dt['SabtuTutup'] . "'
        ,Minggu = '" . $dt['Minggu'] . "'
        ,MingguTutup = '" . $dt['MingguTutup'] . "'
        ,JamBukaPendaftaran = '" . $dt['JamBukaPendaftaran'] . "'
        ,JamTutupPendaftaran = '" . $dt['JamTutupPendaftaran'] . "'
        ,Keterangan = '" . $dt['Keterangan'] . "'
        ,UserUpdate = '" . $dt['UserUpdate'] . "'
        ,UpdateTime = '" . $dt['UpdateTime'] . "'
        WHERE KodeKlinik = '" . $dt['KodeKlinik'] . "' AND JenisWaktu = '" . $dt['JenisWaktu'] . "' AND KodeDokter = '" . $dt['KodeDokter'] . "' ");
    }

    function hapusJadwalPoli($dt)
    {
        return $this->db->query("DELETE FROM RegJadwalKlinik 
        WHERE KodeKlinik = '" . $dt['KodeKlinik'] . "'
        AND JenisWaktu = '" . $dt['JenisWaktu'] . "'
        AND KodeDokter = '" . $dt['KodeDokter'] . "'
        ");
    }
}
