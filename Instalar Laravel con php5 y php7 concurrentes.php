20230106

Para instalar un proyecto Laravel con PHP 5 concurrente con proyectos de PHP 7 en un servidor LAMP en Ubuntu, puedes seguir los siguientes pasos:

1. Instala PHP 5 y el módulo Apache para PHP 5 en tu servidor Ubuntu:

sudo apt-get install php5 libapache2-mod-php5

2. Crea un nuevo directorio para tu proyecto Laravel en el directorio /var/www/:

sudo mkdir -p /var/www/mi_proyecto_laravel

3. Asigna permisos de escritura al directorio del proyecto:


sudo chown -R $USER:$USER /var/www/mi_proyecto_laravel

4. Crea un archivo .htaccess en el directorio del proyecto con el siguiente contenido:


AddType application/x-httpd-php5 .php

Este archivo indica al servidor Apache que utilice PHP 5 para procesar los archivos .php del proyecto.

5. Crea un archivo de configuración virtual host para tu proyecto en /etc/apache2/sites-available/:


<VirtualHost *:80>
    ServerName mi_proyecto_laravel.com
    DocumentRoot /var/www/mi_proyecto_laravel/public
    <Directory /var/www/mi_proyecto_laravel/public>
        AllowOverride All
        Order allow,deny
        allow from all
    </Directory>
</VirtualHost>

6.Habilitar el sitio virtual y reiniciar Apache:

sudo a2ensite mi_proyecto_laravel.conf
sudo service apache2 restart

7. Instala Laravel en el directorio del proyecto:

composer create-project --prefer-dist laravel/laravel .

Con estos pasos, deberías tener un proyecto Laravel funcionando con PHP 5 concurrente con otros proyectos que utilicen PHP 7 en el mismo servidor. Recuerda que necesitarás tener instalado composer para poder ejecutar el comando de instalación de Laravel.