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