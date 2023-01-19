Para conectar una base de datos MySQL en un proyecto de Laravel, primero debes asegurarte de tener un servidor MySQL corriendo y una base de datos creada. Luego, debes agregar la información de conexión a tu archivo de configuración .env. Este archivo se encuentra en la raíz de tu proyecto de Laravel.

Abre el archivo .env y encontrarás una sección llamada DB_CONNECTION. Ahí debes rellenar los siguientes campos:

Copy code
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_de_tu_base_de_datos
DB_USERNAME=nombre_de_usuario
DB_PASSWORD=contraseña
Reemplaza nombre_de_tu_base_de_datos, nombre_de_usuario y contraseña con los valores correspondientes a tu servidor MySQL.

Una vez que hayas agregado esta información, puedes utilizar el gestor de base de datos de Laravel como de costumbre para conectarte a tu base de datos MySQL.




Gabriel Antonio Del Real Márquez
el archivo config\database.php no se ocupa en laravel 
Correcto, en Laravel a partir de la versión 5.5 el archivo config/database.php ya no se utiliza para configurar la conexión a la base de datos. En su lugar, la configuración se guarda en el archivo .env en la raíz del proyecto.

Puedes especificar la configuración de la base de datos en el archivo .env utilizando las variables de entorno que se mencionaron anteriormente. Luego, puedes acceder a estas variables desde tu aplicación usando la función env de PHP. Por ejemplo:

Copy code
$database = env('DB_DATABASE', 'forge');
Este código obtiene el valor de la variable de entorno DB_DATABASE o, si no está definida, utiliza el valor predeterminado forge.

Es importante tener en cuenta que el archivo .env no se versiona en Git, por lo que debes asegurarte de que cada desarrollador o servidor tenga una copia de este archivo con la configuración correcta.

Para conectar una base de datos MySQL 8.0.28 en RDS de AWS con un proyecto Laravel en PHP versión 5, siga estos pasos:

Asegúrese de tener los detalles de conexión de la base de datos, como el nombre de host, el nombre de usuario, la contraseña y el nombre de la base de datos.

Abra el archivo ".env" en el directorio raíz del proyecto Laravel y actualice las siguientes líneas con los detalles de conexión de la base de datos RDS:

Copy code
DB_CONNECTION=mysql
DB_HOST=nombre_de_host
DB_PORT=3306
DB_DATABASE=nombre_de_base_de_datos
DB_USERNAME=nombre_de_usuario
DB_PASSWORD=contraseña
Ahora, en el terminal, ejecute el siguiente comando para asegurarse de que la conexión a la base de datos es correcta:
Copy code
php artisan config:cache
Finalmente, ejecuta en terminal el siguiente comando para verificar la conexión a la base de datos
Copy code
php artisan config:cache
Si la conexión es exitosa, no se mostrará ningún mensaje de error. Si se produce un error, revise los detalles de conexión y asegúrese de que la configuración del firewall en RDS permita la conexión desde la dirección IP de su servidor.