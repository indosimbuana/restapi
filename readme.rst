###################
Catalogue
###################

*******************
Registrasi Akun
*******************

URL Request : http://hostname/restapi/regakun

Method POST

Data yang dikirim berupa format JSON dengan struktur:

{
	"nama": "nama akun",
	"email": "email pengguna",
	"telp": "no telpon pengguna",
	"password": "password pengguna (max. 50 karakter)",
	"level": "level akun, didapat dari load ws mstlevelakun, kirim kode levelnya (IDLevel) saja"
}


**************************
Login Akun
**************************

URL Request : http://hostname/restapi/loginakun

Method POST

Data yang dikirim berupa format JSON dengan struktur:

{
	"user": "nama akun atau email atau no telpon",
	"password": "password pengguna"
}

**************************
Ubah Password
**************************

URL Request : http://hostname/restapi/ubahpassword

Method POST

Data yang dikirim berupa format JSON dengan struktur:

{
	"user": "nama akun atau email atau no telpon",
	"password": "password sekarang",
	"passwordbaru": "password baru"
}

**************************
Lupa Password dan Akun
**************************

URL Request : http://hostname/restapi/lupa

Method POST

Data yang dikirim berupa format JSON dengan struktur:

{
	"type": "password (untuk lupa password) atau akun (untuk lupa akun)",
	"user": "email atau no telp atau nama akun (kondisional tergantung parameter type di atas)"
}

**************************
Ubah Email
**************************

URL Request : http://hostname/restapi/ubahemail

Method POST

Data yang dikirim berupa format JSON dengan struktur:

{
	"user": "email atau no telp atau nama akun",
	"password": "password saat ini, untuk konfirmasi kebenaran akun",
	"email": "email baru"
}

**************************
Ubah Telpon
**************************

URL Request : http://hostname/restapi/ubahtelp

Method POST

Data yang dikirim berupa format JSON dengan struktur:

{
	"user": "email atau no telp atau nama akun",
	"password": "password saat ini, untuk konfirmasi kebenaran akun",
	"telp": "no telpon baru"
}

**************************
Reset Password 
**************************

URL Request : http://hostname/restapi/resetpassword

Method POST

Reset password dijalankan setelah dapat email kode permintaan lupa password. Data yang dikirim berupa format JSON dengan struktur:

{
	"telp": "no telp",
	"kode": "kode lupa password dari email pengguna",
	"passwordbaru": "password baru"
}

**************************
Cari Pasien Lama
**************************

URL Request : http://hostname/restapi/cekpasienlama

Method POST

{
	"nopasien": "no pasien",
	"tgllahir": "yyyy-mm-dd"
}

**************************
Master Level Akun 
**************************

URL Request All Data : http://hostname/restapi/mstlevelakun

URL Request Data By ID : http://hostname/restapi/mstlevelakun?id=<idlevel>

Method GET

Master Level Akun digunakan untuk registrasi user dengan level tertentu.

**************************
Master Provinsi 
**************************

URL Request All Data : http://hostname/restapi/mstprovinsi

URL Request Data By ID : http://hostname/restapi/mstprovinsi?id=<kodeprovinsi>

Method GET

**************************
Master Kabupaten 
**************************

URL Request All Data : http://hostname/restapi/mstkabupaten

URL Request Data By ID Provinsi : http://hostname/restapi/mstkabupaten?prov=<kodeprovinsi>

Method GET

**************************
Master Kecamatan 
**************************

URL Request All Data : http://hostname/restapi/mstkecamatan?prov=0014&kab=0002

URL Request Data By ID Provinsi & ID Kabupaten : http://hostname/restapi/mstkecamatan?prov=<kodeprovinsi>&kab=<kodekabupaten>

Method GET

**************************
Master Agama 
**************************

URL Request All Data : http://hostname/restapi/mstagama

URL Request Data By ID : http://hostname/restapi/mstagama?id=<kodeagama>

Method GET

**************************
Master Golongan Darah 
**************************

URL Request All Data : http://hostname/restapi/mstgoldarah

URL Request Data By ID : http://hostname/restapi/mstgoldarah?id=<idgoldarah>

Method GET

**************************
Master Pendidikan 
**************************

URL Request All Data : http://hostname/restapi/mstpendidikan

URL Request Data By ID : http://hostname/restapi/mstpendidikan?id=<idpendidikan>

Method GET

**************************
Master Pekerjaan 
**************************

URL Request All Data : http://hostname/restapi/mstpekerjaan

URL Request Data By ID : http://hostname/restapi/mstpekerjaan?id=<idpekerjaan>

Method GET

**************************
Master Status Kawin 
**************************

URL Request All Data : http://hostname/restapi/mststatuskawin

URL Request Data By ID : http://hostname/restapi/mststatuskawin?id=<idstatuskawin>

Method GET

**************************
Master Penjamin 
**************************

URL Request All Data : http://hostname/restapi/mstpenjamin

URL Request Data By ID : http://hostname/restapi/mstpenjamin?id=<idpenjamin>

Method GET

**************************
Master Bahasa 
**************************

URL Request All Data : http://hostname/restapi/mstbahasa

URL Request Data By ID : http://hostname/restapi/mstbahasa?id=<idbahasa>

Method GET

**************************
Master Suku 
**************************

URL Request All Data : http://hostname/restapi/mstsuku

URL Request Data By ID : http://hostname/restapi/mstsuku?id=<idsuku>

Method GET

**************************
Pilih Poli
**************************

URL Request : http://hostname/restapi/pilihpoli?jns=<jeniswaktupoli>&tgl=<tanggalperiksa>

Method GET

Format Parameter:
- jns : P atau S
- tgl : yyyy-mm-dd (ex. 2020-08-23)

Poli anak pada hari Sabtu minggu ke-2 dan ke-4 libur

**************************
Pilih Tanggal Periksa 
**************************

URL Request : http://hostname/restapi/pilihtanggal

Method GET

Skip hari Minggu dan hari libur, H+7 H-1

**************************
Tambah Anggota Keluarga 
**************************

URL Request : http://hostname/restapi/reganggota

Method POST

Pasien Baru :

{
	"hubungan": "<max 50 varchar>",
	"idakun":"<namauser akun pendaftar>",
	"namalengkap":"<namalengkap max 300 varchar>",
	"ktp":"<noktp untuk pasien baru wajib diisi ya, untuk pengecekan data>",
	"jeniskelamin":"<jeniskelamin L/P>",
	"tempatlahir":"<tmptlahir max 100 varchar>",
	"tgllahir":"<tgllahir format yyyy-mm-dd ex. 1990-01-01>",
	"alamat":"<alamat dgn kelurahan max 500 varchar>",
	"provinsi":"<kodeprovinsi>",
	"kabupaten":"<kodekabupaten>",
	"kecamatan":"<kodekecamatan>",
	"kodepos":"<kodepos>",
	"agama":"<kodeagama>",
	"goloangandarah":"<kodegoldarah>",
	"pendidikan":"<kodependidikan>",
	"statuskawin":"<kodestatuskawin>",
	"pekerjaan":"<kodepekerjaan>",
	"wni":"<wni 1/0>",
	"negara":"<negara text max 15 char>",
	"suku":"<kodesuku>",
	"bahasa":"<kodebahasa>",
	"alergi":"<text max 40 varchar>",
	"alamatkantor":"<max 500 varchar>",
	"telpkantor":"<notelp>",
	"namakeluarga":"<namakeluarga max 200 varchar>",
	"namaayah":"<namaayah max 200 varchar>",
	"namaibu":"<namaibu max 200 varchar>",
	"namasuamiistri":"<namasuamiistri max 200 varchar>",
	"notelpon":"<notelp max 14 char>",
	"email":"<email max 50 varchar>"
}

Pasien Lama:

{
	"nopasien": "<nopasien>",
	"tgllahir": "<yyyy-mm-dd>",
	"idakun": "<idakun>",
	"hubungan": "<hubungan>"
}

Jika parameter nopasien kosong atau null maka dianggap sebagai pasien baru.
Pasien lama hanya membutuhkan parameter hubungan, idakun, nopasien, dan parameter yang lain tetap dikirimkan.
Untuk link pasien baru dan lama sama saja, dan parameter yang dikirimkan juga jumlahnya sama, yang membedakan hanya pada kondisi value dari parameter nopasien.

**************************
Simpan Booking 
**************************

URL Request : http://hostname/restapi/regbooking

Method POST

{
"idanggotakeluarga": "<idanggotakeluarga>",
"bagian":"<kodebagian>",
"penjamin":"<kodepenjamin>",
"nopenjamin":"<no kartu penjamin>",
"norujukan":"<norujukan jika bpjs",
"dokter":"<kodedokter>",
"tanggal":"<yyyy-mm-dd>",
"waktu":"<P/S>"
}

**************************
Anggota Keluarga 
**************************

URL Request : http://hostname/restapi/anggotakeluarga?akun=<namaakun>

Method GET

**************************
Riwayat Booking 
**************************

URL Request Daftar Riwayat : http://hostname/restapi/riwayatreg?anggota=<idanggotakeluarga>

URL Request Detail Riwayat : http://hostname/restapi/riwayatreg?kode=<kodebooking>

Method GET

**************************
Jadwal Klinik 
**************************

URL Request Semua Klinik : http://hostname/restapi/jadwalpoli

URL Request Klinik Tertentu : http://hostname/restapi/jadwalpoli?id=<idklinik>

Method GET

**************************
Kritik Saran
**************************

URL Request Semua Kritik Saran : http://hostname/restapi/kritiksaran

URL Request Kritik Saran By Id : http://hostname/restapi/kritiksaran?id=<idkritsar>

URL Request Kritik Saran By Bagian KS : http://hostname/restapi/kritiksaran?bag=<kodebagian>

Method GET

URL Request Kirim Kritik Saran : http://hostname/restapi/kritiksaran

Method POST

{
	"idakun": "<idakun>",
	"nama": "<nama>",
	"alamat": "<alamat>",
	"bagian": "<kodebagianKS>",
	"telp": "<telp>",
	"email": "<email>",
	"kritiksaran": "<isi>"
}

URL Request Jawab Kritik Saran : http://hostname/restapi/kritiksaran/jawab

{
	"idkritsar": "<idkritsar>",
	"jawaban": "<jawaban>",
	"penjawab": "<idakunpenjawab>"
}

Method POST

**************************
Bagian Kritik Saran 
**************************

URL Request Semua Bagian : http://hostname/restapi/mstbagianks

URL Request Bagian By Id : http://hostname/restapi/mstbagianks?id=<idbagian>

Method GET

**************************
Master Poli 
**************************

URL Request Semua Poli : http://hostname/restapi/mstpoli

URL Request Poli By Id : http://hostname/restapi/mstpoli?id=<idpoli>

Method GET

**************************
Insert, update, delete jadwal poli
**************************

URL Tambah Jadwal Poli : http://hostname/restapi/jadwalpoli
Method POST

URL Update Jadwal Poli : http://hostname/restapi/jadwalpoli
Method PUT

{ 
	"KodeKlinik": "6104", 
	"JenisWaktu":"S", 
	"KodeDokter":"DR03", 
	"Senin":"07:00", 
	"SeninTutup":"12:00", 
	"Selasa":"09:00", 
	"SelasaTutup":"13:00", 
	"Rabu":"08:00", 
	"RabuTutup":"13:00",
	"Kamis":"07:00", 
	"KamisTutup":"10:00", 
	"Jumat":"08:00", 
	"JumatTutup":"09:00", 
	"Sabtu":"09:00", 
	"SabtuTutup":"17:00",
	"Minggu":"", 
	"MingguTutup":"", 
	"JamBukaPendaftaran":"07:00", 
	"JamTutupPendaftaran":"10:00",
	"Keterangan":"Jadwal Fleksibel",
	"UserUpdate":"agung"
}

Rating

{ 
	"KodeBooking": "202011230004", 
	"KodeTanya":"20201124101800", 
	"NilaiRating":"5", 
	"UserRating":"hapsari"
}

URL Delete Jadwal Poli : http://hostname/restapi/jadwalpoli?KodeKlinik=<kodeklinik>&JenisWaktu=<P atau S>&KodeDokter=<kodedokter>
Method DELETE

###################
Instalasi Aplikasi
###################

**************************
Tools
**************************

- Git

- xampp-windows-x64-7.4.8-0-VC15-installer PHP Version 7.4.8

- sqlncli

- SQL Server Driver PHP (SQLSRV58 php_sqlsrv_74_ts_x64.dll dan php_pdo_sqlsrv_74_ts_x64.dll)

**************************
Petunjuk
**************************

- Instal Git, download di https://git-scm.com/download/win

- Instal XAMPP, download di https://downloadsapachefriends.global.ssl.fastly.net/7.4.8/xampp-windows-x64-7.4.8-0-VC15-installer.exe?from_af=true

- Instal sqlncli, download di http://download.microsoft.com/download/F/E/D/FEDB200F-DE2A-46D8-B661-D019DFE9D470/ENU/x64/sqlncli.msi

- Instal SQL Server Driver PHP dengan cara download di https://go.microsoft.com/fwlink/?linkid=2120362, lalu extract file, copy php_sqlsrv_74_ts_x64.dll dan php_pdo_sqlsrv_74_ts_x64.dll, paste di folder DRIVE:\xampp\php\ext, edit file php.ini di DRIVE:\xampp\php dan tamahkan extension=php_sqlsrv_74_ts_x64.dll dan extension=php_pdo_sqlsrv_74_ts_x64.dll pada bagian baris extension, restart service apache

- Clone project dengan cara masuk ke folder DRIVE:\xampp\htdocs buka aplikasi Git Bash dengan cara klik kanan pada explorer kosong, pilih Git Bash Here dan ketik perintah: git clone https://github.com/agungphe/restapi.git

- Service siap digunakan dengan method sesuai Catalogue di atas