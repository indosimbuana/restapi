<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mreganggota extends CI_Model
{

    function getAkunByNama($id)
    {
        return $this->db->query("SELECT * FROM RegAkun WHERE NamaAkun = '$id'")->row();
    }

    function getPasienLama($id)
    {
        return $this->db->query("SELECT * FROM MasterPasien WHERE Nopasien = '$id'")->row();
    }

    function getAnggotaKeluarga($id)
    {
        return $this->db->query("SELECT * FROM RegAnggotaKeluarga WHERE idAnggotaKeluarga = '$id'")->row();
    }

    function getAnggotaPasienLama($akun, $nopasien)
    {
        return $this->db->query("SELECT * FROM RegAnggotaKeluarga WHERE idAkun = '$akun' AND noPasien = '$nopasien'")->row();
    }

    function getAnggotaPasienBaru($akun, $ktp)
    {
        return $this->db->query("SELECT * FROM RegAnggotaKeluarga WHERE idAkun = '$akun' AND KTP = '$ktp'")->row();
    }

    function simpanPasienLama($data)
    {
        return $this->db->query("INSERT INTO RegAnggotaKeluarga (
            idAnggotaKeluarga
            , idAkun
            , hubunganAkun
            , noPasien
            , NamaLengkap
            , NamaPanggilan
            , TglLahir
            , NoTelpon
            , Email
        )
        VALUES (
        " . $this->db->escape($data['idanggotakeluarga']) . "
        ," . $this->db->escape($data['idakun']) . "
        ," . $this->db->escape($data['hubungan']) . "
        ," . $this->db->escape($data['nopasien']) . "
        ," . $this->db->escape($data['namalengkap']) . "
        ," . $this->db->escape($data['namapanggilan']) . "
        ," . $this->db->escape($data['tgllahir']) . "
        ," . $this->db->escape($data['notelpon']) . "
        ," . $this->db->escape($data['email']) . ");");
    }

    function simpanPasienBaru($data)
    {
        return $this->db->query("INSERT INTO RegAnggotaKeluarga (
            idAnggotaKeluarga
            ,idAkun
            ,hubunganAkun
            ,NamaLengkap
            ,NamaPanggilan
            ,KTP
            ,JenisKelamin
            ,TempatLahir
            ,TglLahir
            ,Alamat
            ,RT
            ,RW
            ,Provinsi
            ,Kabupaten
            ,Kecamatan
            ,KodePOS
            ,Agama
            ,GolonganDarah
            ,Pendidikan
            ,StatusKawin
            ,Pekerjaan
            ,WNI
            ,Negara
            ,Suku
            ,Bahasa
            ,Alergi
            ,AlamatKantor
            ,TelpKantor
            ,NamaKeluarga
            ,NamaAyah
            ,NamaIbu
            ,NamaSuamiIstri
            ,NoTelpon
            ,Email
        )
        VALUES (
        " . $this->db->escape($data['idanggotakeluarga']) . "
        ," . $this->db->escape($data['idakun']) . "
        ," . $this->db->escape($data['hubungan']) . "
        ," . $this->db->escape($data['namalengkap']) . "
        ," . $this->db->escape($data['namapanggilan']) . "
        ," . $this->db->escape($data['ktp']) . "
        ," . $this->db->escape($data['jeniskelamin']) . "
        ," . $this->db->escape($data['tempatlahir']) . "
        ," . $this->db->escape($data['tgllahir']) . "
        ," . $this->db->escape($data['alamat']) . "
        ," . $this->db->escape($data['rt']) . "
        ," . $this->db->escape($data['rw']) . "
        ," . $this->db->escape($data['provinsi']) . "
        ," . $this->db->escape($data['kabupaten']) . "
        ," . $this->db->escape($data['kecamatan']) . "
        ," . $this->db->escape($data['kodepos']) . "
        ," . $this->db->escape($data['agama']) . "
        ," . $this->db->escape($data['goloangandarah']) . "
        ," . $this->db->escape($data['pendidikan']) . "
        ," . $this->db->escape($data['statuskawin']) . "
        ," . $this->db->escape($data['pekerjaan']) . "
        ," . $this->db->escape($data['wni']) . "
        ," . $this->db->escape($data['negara']) . "
        ," . $this->db->escape($data['suku']) . "
        ," . $this->db->escape($data['bahasa']) . "
        ," . $this->db->escape($data['alergi']) . "
        ," . $this->db->escape($data['alamatkantor']) . "
        ," . $this->db->escape($data['telpkantor']) . "
        ," . $this->db->escape($data['namakeluarga']) . "
        ," . $this->db->escape($data['namaayah']) . "
        ," . $this->db->escape($data['namaibu']) . "
        ," . $this->db->escape($data['namasuamiistri']) . "
        ," . $this->db->escape($data['notelpon']) . "
        ," . $this->db->escape($data['email']) . "
        );");
    }
}
