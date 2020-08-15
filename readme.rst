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
	"password": "password pengguna (max. 50 karakter)"
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