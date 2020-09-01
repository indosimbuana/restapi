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
        return $this->db->query("SELECT * FROM KritikSaran ks LEFT JOIN RegBagianKS bk ON bk.KodeBagian = ks.kodeBagian ORDER BY ks.tglTanya DESC")->result_array();
    }

    function getKritSarByBag($id)
    {
        return $this->db->query("SELECT * FROM KritikSaran ks LEFT JOIN RegBagianKS bk ON bk.KodeBagian = ks.kodeBagian WHERE ks.kodeBagian = '$id' ORDER BY ks.tglTanya DESC")->result_array();
    }

    function kirimKritSar($data)
    {
        return $this->db->query("INSERT INTO KritikSaran (idKritSar, idAkun, kritik, saran, kodeBagian)
        VALUES (" . $this->db->escape($data['idKritSar']) . "," . $this->db->escape($data['idAkun']) . "," . $this->db->escape($data['kritik']) . "," . $this->db->escape($data['saran']) . "," . $this->db->escape($data['kodeBagian']) . ");");
    }

    function jawabKritSar($data)
    {
        return $this->db->query("UPDATE KritikSaran SET jawaban = " . $this->db->escape($data['jawaban']) . ", status = " . $this->db->escape($data['status']) . ", penjawab = " . $this->db->escape($data['penjawab']) . ", tglJawab = " . $this->db->escape($data['tglJawab']) . "  WHERE (idKritSar = '" . $data['idKritSar'] . "') ");
    }
}
