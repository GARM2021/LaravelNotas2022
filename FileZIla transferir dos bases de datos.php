para usar el File Zilla y comunicar dos servidores Sql Server que requistos hay que tener por ejemplo activar el servicio ftp  pudiera ser uno
Si deseas usar FileZilla para transferir archivos entre dos servidores SQL Server, necesitas asegurarte de que ambos servidores tengan configurado un servidor FTP o SFTP (FTP seguro).

Aquí te dejo algunos requisitos que necesitas tener en cuenta:

Configurar un servidor FTP o SFTP en ambos servidores SQL Server.
Asegurarte de que los servidores SQL Server tengan acceso a Internet.
Descargar e instalar FileZilla en tu computadora.
Conocer las credenciales de acceso a los servidores SQL Server (nombre de usuario, contraseña y puerto de FTP o SFTP).
Tener permisos de escritura y lectura en los directorios de los servidores SQL Server que deseas acceder.
Una vez que hayas cumplido con estos requisitos, puedes seguir estos pasos para transferir archivos entre servidores SQL Server con FileZilla:

Abre FileZilla y conecta a uno de los servidores SQL Server utilizando las credenciales de acceso al servidor FTP o SFTP.
En el panel izquierdo de FileZilla, navega hasta la ubicación del archivo que deseas transferir.
Arrastra el archivo desde el panel izquierdo de FileZilla hasta el panel derecho para transferirlo al servidor local.
Después de la transferencia, desconecta del servidor y conecta al otro servidor SQL Server utilizando las credenciales de acceso al servidor FTP o SFTP.
Navega hasta la ubicación donde deseas guardar el archivo en el segundo servidor SQL Server.
Arrastra el archivo desde el panel derecho de FileZilla hasta el panel izquierdo para transferirlo al segundo servidor.
Recuerda que al transferir archivos a través de FTP o SFTP, los archivos se transmiten en texto plano, lo que significa que pueden ser vulnerables a ataques de sniffing de red. Es recomendable utilizar SFTP (FTP seguro) en lugar de FTP regular para una transferencia de archivos más segura.



