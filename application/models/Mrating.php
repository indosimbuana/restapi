<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mrating extends CI_Model
{

    function cekPasien($akun)
    {
        return $this->db->query("SELECT ro.KodeBooking, ro.idAnggotaKeluarga FROM RegOnline ro
        LEFT JOIN RegAnggotaKeluarga ak ON ak.idAnggotaKeluarga = ro.idAnggotaKeluarga
        LEFT JOIN RegPasien rp ON rp.Nobooking = ro.kodeBooking
        LEFT JOIN RegDokter rd ON rd.NoRegistrasi = rp.NoRegistrasi
        WHERE ak.idAkun = '$akun' AND (rd.NoInvoice != 0 OR rd.NoInvoice != '')")->result_array();
    }

    function getRating()
    {
        return $this->db->query("SELECT * FROM PertanyaanRating WHERE Aktif = 'Y'")->result_array();
    }

    function kirimRating($data)
    {
        return $this->db->query("INSERT INTO DaftarRating (
            KodeBooking
            ,KodeTanya
            ,NilaiRating
            ,UserRating
            )
            VALUES (
            " . $this->db->escape($data['KodeBooking']) . "
            ," . $this->db->escape($data['KodeTanya']) . "
            ," . $this->db->escape($data['NilaiRating']) . "
            ," . $this->db->escape($data['UserRating']) . "
        );");
    }
}
