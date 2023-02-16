20230210

Aqui se basaron para instalar el WebServGpe

https://ubunlog.com/laravel-framework-php-ubuntu/#Instalar_Laravel



De aqui se bajaron los drivers 

https://tutoriales.cect.org/instalar-drivers-de-microsoft-sql-server-en-php-5-6-debian-y-apache-2/
Installing MSSQL for PHP

En esencia se trata de instalar tres paquetes:

libsybdb5
freetds-common
php5-sybase
Para ello ejecutamos:

apt-get install libsybdb5 freetds-common php5-sybase
/etc/init.d/apache2 restart
Una vez arrancado apache2 de nuevo ya tenemos el módulo mssql funcionando.

CODIFICACIÓN DE CARACTERES DE LA CONEXIÓN CON LA BASE DE DATOS
Para forzar que la conexión devuelva caracteres en UTF-8 hemos de poner lo siguiente en la sección [global] del archivo de configuración de freedts (/etc/freedts/freedts.conf):
tds version = 8.0
client charset = UTF-8
