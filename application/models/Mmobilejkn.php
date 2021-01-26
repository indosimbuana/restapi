<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mmobilejkn extends CI_Model
{
    function cekAkun($dt)
    {
        return $this->db->query("SELECT * FROM JKN_User WHERE NamaAkun = '" . $dt['username'] . "' AND password = '" . $dt['password'] . "'")->row();
    }

    function getPasienServerBaru($nokartu, $nik)
    {
        return $this->db->query("SELECT * FROM MasterPasien
        WHERE NoKartuBPJS = '$nokartu' OR NoKTP = '$nik'")->row();
    }

    function getPasienServerLama($nokartu, $nik)
    {
        $db2 = $this->load->database('serverlama', TRUE);
        return $db2->query("SELECT NORM AS Nopasien, NAMA AS NamaPasien, NamaPanggilan AS NamaPanggilan, TEMPATLAHIR AS TempatLahir, TGLLAHIR AS TglLahir, JNSKELAMIN AS JenisKelamin, ALAMAT AS AlamatPasien, RT AS RT, RW AS RW, NEGARA AS Negara, KODEPOS AS KodePos, NOTELP AS TlpPasien, NamaPasangan AS NamaSuamiIstri, NOKTP AS NoKTP, NOKARTUJAMKES AS NoKartuBPJS 
        FROM tbpasien 
        WHERE NOKARTUJAMKES = '$nokartu' OR NOKTP = '$nik'")->row();
    }

    function getKodePoli($kode)
    {
        return $this->db->query("SELECT * FROM MapingPoliBPJS WHERE KodeBPJS = '$kode'")->row();
    }

    function getJamPoli($bag, $wkt)
    {
        return $this->db->query("SELECT rj.*, md.NamaDokter, mi.NamaBagian FROM RegJadwalKlinik rj
        LEFT JOIN MasterDokter md ON md.KodeDokter = rj.KodeDokter
        LEFT JOIN MasterInstalasi mi ON mi.KodeBagian = rj.KodeKlinik
        WHERE rj.KodeKlinik = '$bag' AND rj.JenisWaktu = '$wkt'")->row();
    }

    function getJamPoliKebidanan($wkt, $hari)
    {
        return $this->db->query("SELECT rj.*, md.NamaDokter, mi.NamaBagian FROM RegJadwalKlinik rj
        LEFT JOIN MasterDokter md ON md.KodeDokter = rj.KodeDokter
        LEFT JOIN MasterInstalasi mi ON mi.KodeBagian = rj.KodeKlinik
        WHERE rj.KodeKlinik IN ('6101','6107') AND rj.JenisWaktu = '$wkt' AND ($hari != '00:00:00.00000' OR $hari IS NOT NULL)")->row();
    }

    function cekRegPasienJkn($nik, $tgl, $poli)
    {
        return $this->db->query("SELECT * FROM RegOnline ro
        LEFT JOIN RegAnggotaKeluarga ra ON ra.idAnggotaKeluarga = ro.idAnggotaKeluarga
        WHERE ra.KTP = '$nik' AND DATEDIFF(DAY, ro.tglPemeriksaan, '$tgl') = 0 AND ro.kodeBagian = '$poli'")->row();
    }

    function cekPasienJkn($nik)
    {
        return $this->db->query("SELECT * FROM RegAnggotaKeluarga
        WHERE idAkun = 'JKN' AND KTP = '$nik'")->row();
    }

    function getJadwalOperasi($nopeserta)
    {
        return $this->db->query("SELECT jo.*, mt.NAMAPMR, mb.KodeBPJS, mi.NamaBagian FROM JKN_JadwalOP jo
        LEFT JOIN MasterPasien mp ON mp.Nopasien = jo.Nopasien
        LEFT JOIN MasterTindakan mt ON mt.KODEPMR = jo.KodeTindakan
        LEFT JOIN MapingPoliBPJS mb ON mb.RuangId = jo.KodeBagian
        LEFT JOIN MasterInstalasi mi ON mi.KodeBagian = mb.RuangId
        WHERE mp.NoKartuBPJS = '$nopeserta' AND jo.Terlaksana = 0 AND jo.Batal = 0 ORDER BY jo.TglOperasi DESC")->result_array();
    }

    function getListJadwalOperasi($awal, $akhir)
    {
        return $this->db->query("SELECT jo.*, mt.NAMAPMR, mb.KodeBPJS, mi.NamaBagian, mp.NoKartuBPJS FROM JKN_JadwalOP jo
        LEFT JOIN MasterPasien mp ON mp.Nopasien = jo.Nopasien
        LEFT JOIN MasterTindakan mt ON mt.KODEPMR = jo.KodeTindakan
        LEFT JOIN MapingPoliBPJS mb ON mb.RuangId = jo.KodeBagian
        LEFT JOIN MasterInstalasi mi ON mi.KodeBagian = mb.RuangId
        WHERE (jo.TglOperasi BETWEEN '$awal' AND '$akhir') ORDER BY jo.TglOperasi DESC")->result_array();
    }

    function simpanPasienLama($data)
    {
        return $this->db->query("INSERT INTO RegAnggotaKeluarga (
            idAnggotaKeluarga
            ,idAkun
            ,hubunganAkun
            ,noPasien
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
        ," . $this->db->escape($data['nopasien']) . "
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
