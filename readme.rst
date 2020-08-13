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