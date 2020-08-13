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

###################
Instalasi Aplikasi
###################

**************************
Tools
**************************

` Git
` xampp-windows-x64-7.4.8-0-VC15-installer PHP Version 7.4.8
` sqlncli
` SQL Server Driver PHP (SQLSRV58 php_sqlsrv_74_ts_x64.dll dan php_pdo_sqlsrv_74_ts_x64.dll)

**************************
Petunjuk
**************************

` Instal Git
` Instal XAMPP, download di https://downloadsapachefriends.global.ssl.fastly.net/7.4.8/xampp-windows-x64-7.4.8-0-VC15-installer.exe?from_af=true
` Instal sqlncli, download di http://download.microsoft.com/download/F/E/D/FEDB200F-DE2A-46D8-B661-D019DFE9D470/ENU/x64/sqlncli.msi
` Instal SQL Server Driver PHP dengan cara download di https://go.microsoft.com/fwlink/?linkid=2120362, lalu extract file, copy php_sqlsrv_74_ts_x64.dll dan php_pdo_sqlsrv_74_ts_x64.dll, paste di folder DRIVE:\xampp\php\ext, edit file php.ini di DRIVE:\xampp\php dan tamahkan extension=php_sqlsrv_74_ts_x64.dll dan extension=php_pdo_sqlsrv_74_ts_x64.dll pada bagian baris extension, restart service apache
` Clone project dengan cara masuk ke folder DRIVE:\xampp\htdocs buka aplikasi Git Bash dengan cara klik kanan pada explorer kosong, pilih Git Bash Here dan ketik perintah: git clone https://github.com/agungphe/restapi.git
` Service siap digunakan dengan method sesuai Catalogue di atas