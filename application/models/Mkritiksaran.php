<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mkritiksaran extends CI_Model
{
    function cekKritSar($dt)
    {
        return $this->db->query("SELECT * FROM KritikSaran ks LEFT JOIN RegBagianKS bk ON bk.KodeBagian = ks.kodeBagian WHERE ks.idKritSar = '" . $dt . "'")->row();
    }

    function getKritSar()
    {
        return $this->db->query("SELECT * FROM KritikSaran ks LEFT JOIN RegBagianKS bk ON bk.KodeBagian = ks.kodeBagian ORDER BY ks.status,ks.tglTanya DESC")->result_array();
    }

    function getKritSarByAkun($akun)
    {
        return $this->db->query("SELECT * FROM KritikSaran ks LEFT JOIN RegBagianKS bk ON bk.KodeBagian = ks.kodeBagian WHERE ks.idAkun = '$akun' ORDER BY ks.status,ks.tglTanya DESC")->result_array();
    }

    function getKritSarByTgl($tgl)
    {
        return $this->db->query("SELECT * FROM KritikSaran ks LEFT JOIN RegBagianKS bk ON bk.KodeBagian = ks.kodeBagian WHERE DATEDIFF(DAY, ks.tglTanya, '$tgl') = 0 ORDER BY ks.status,ks.tglTanya DESC")->result_array();
    }

    function getKritSarByBag($id)
    {
        return $this->db->query("SELECT * FROM KritikSaran ks LEFT JOIN RegBagianKS bk ON bk.KodeBagian = ks.kodeBagian WHERE ks.kodeBagian = 'KS03' ORDER BY ks.status,ks.tglTanya DESC")->result_array();
    }

    function getKritSarByBagByTgl($id, $tgl)
    {
        return $this->db->query("SELECT * FROM KritikSaran ks LEFT JOIN RegBagianKS bk ON bk.KodeBagian = ks.kodeBagian WHERE ks.kodeBagian = 'KS03' AND DATEDIFF(DAY, ks.tglTanya, '$tgl') = 0 ORDER BY ks.status,ks.tglTanya DESC")->result_array();
    }

    function kirimKritSar($data)
    {
        return $this->db->query("INSERT INTO KritikSaran (
            idKritSar
            ,idAkun
            ,nama
            ,alamat
            ,kodeBagian
            ,telp
            ,email
            ,kritikSaran
            )
            VALUES (
            " . $this->db->escape($data['idKritSar']) . "
            ," . $this->db->escape($data['idAkun']) . "
            ," . $this->db->escape($data['nama']) . "
            ," . $this->db->escape($data['alamat']) . "
            ," . $this->db->escape($data['kodeBagian']) . "
            ," . $this->db->escape($data['telp']) . "
            ," . $this->db->escape($data['email']) . "
            ," . $this->db->escape($data['kritikSaran']) . "
        );");
    }

    function jawabKritSar($data)
    {
        return $this->db->query("UPDATE KritikSaran SET jawaban = " . $this->db->escape($data['jawaban']) . ", status = " . $this->db->escape($data['status']) . ", penjawab = " . $this->db->escape($data['penjawab']) . ", tglJawab = " . $this->db->escape($data['tglJawab']) . "  WHERE (idKritSar = '" . $data['idKritSar'] . "') ");
    }
}
