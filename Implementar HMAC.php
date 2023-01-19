
<?php
Buen día Gabriel.

Gusto en saludarte esperando te la hayas pasado muy bien, te deseo un excelente inicio de año y lo mejor para tu familia, el motivo de mi correo es para iniciar la implementación del hmac, la especificación es la siguiente:

Para cuidar la autenticidad e integridad de los parámetros intercambiados entre la aplicación de Multipagos y la aplicación del Cliente, se usa el algoritmo SHA-256 de dominio público. Para este proceso se utilizará una llave privada que se enviará por separado de manera encriptada al correo del contacto principal de la institución, con la cual se generará una firma digital HMAC (Hash Message Authentication Code).
 
Algunos ejemplos de cómo generar la firma digital (función Hash) en php, .net o java son:
 
http://asecuritysite.com/encryption/hmac
http://www.php.net/manual/en/function.hash-hmac.php
https://gist.github.com/MaximeFrancoeur/bcb7fc2db08c704f322a
https://www.freeformatter.com/hmac-generator.html#ad-output (permite validar el HMAC)
 
Es importante considerar que la firma digital HMAC que se envíe, debe venir en minúsculas para que la aplicación de Multipagos la considere como un valor correcto. Adicionalmente, la cadena que se debe encriptar consta de los parámetros s_transm + c_referencia + t_importe y en el retorno se añade el parámetro n_autoriz (la llave privada no debe formar parte de la cadena a encriptar ni debe ser enviada como parámetro).
 
De igual forma, es muy importante que la llave privada quede resguardada en lugar seguro protegida del alcance de terceros, ya que es un dato sensible que si alguna persona malintencionada tiene acceso a ella, la utilidad del HMAC se vería afectada, pues los parámetros podrían ser modificados y alterar los datos de la transacción.

Me podrías dar una fecha y hora que tengas disponibles para pasarte la contraseña del zip que te adjunte para que puedas implementar el hmac.

Quedo al pendiente,

Saludos.

=======================================================================================================================================================

algoritmo SHA-256 






20220112
algoritmo SHA-256 en PHP Laravel
El algoritmo SHA-256 (Secure Hash Algorithm 256-bit) es una función de hash criptográfica que se utiliza para generar una cadena de texto de 256 bits a partir de una entrada de cualquier tamaño. En Laravel, se puede utilizar la función hash() para generar una cadena SHA-256 de una cadena de texto dada. Por ejemplo:

Copy code
$hashed_password = hash('sha256', $password);
También puede utilizar el método bcrypt() en el modelo de usuario para almacenar contraseñas encriptadas con el algoritmo bcrypt, que incluye el algoritmo SHA-256.

Copy code
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    // ...

    /**
     * Hash the password.
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
En este caso Laravel se encarga de utilizar el algoritmo bcrypt que incluye el algoritmo sha256, para encriptar la contraseña.

========================================================================================================================================


Para implementar HMAC (Keyed-Hash Message Authentication Code) en un proyecto Laravel, hay varios pasos que debes seguir:


Crea una clave secreta para la autenticación.
Esta clave debe ser utilizada tanto en el servidor como en el cliente.
Es importante guardar esta clave en un lugar seguro y no compartirla con nadie.

Agrega un encabezado de autorización a tus peticiones HTTP.
El encabezado debe tener el formato "Authorization: HMAC <mensaje>",
donde <mensaje> es el resultado del código HMAC generado con la clave secreta y la información de la petición.

En el servidor, verifica la autenticidad de la petición.
Para hacer esto, debe calcular el código HMAC utilizando la misma clave secreta y la información de la petición,
y comparar el resultado con el valor enviado en el encabezado de autorización.
Si los valores son diferentes, la petición debe ser rechazada.

Puedes usar Laravel Middleware para verificar la autenticidad de la petición en cada petición de tu aplicación.

Utiliza el paquete encargado de proporcionar HMAC en Laravel como "laravel/passport"
que ofrece una solución fácil de implementar para la autenticación de clave secreta.

Es recomendable utilizar una librería existente para generar y verificar los códigos HMAC.
Laravel proporciona una clase llamada "Hash" que se puede usar para generar y verificar los códigos HMAC.


Es importante mencionar que no debes usar esto para autenticar usuarios sino para asegurar la integridad de la información 
ya que no es seguro para autenticar ya que las claves estan compartidas.


Ademas de esto siempre es recomendable manejar un buen algoritmo de encriptación como AES o RSA para evitar posibles ataques a la seguridad.




Gabriel Antonio Del Real Márquez
Ejemplo de calculo de código HMAC utilizando la misma clave secreta y la información de la petición
Aquí te doy un ejemplo de cómo calcular un código HMAC en Laravel utilizando la clase Hash y la función hmac()

Copy code
use Illuminate\Support\Facades\Hash;

// Clave secreta
$secretKey = "secret_key";

// Información de la petición
$requestData = [
    "nombre" => "John",
    "apellido" => "Doe",
    "email" => "johndoe@example.com",
    "telefono" => "555-555-5555"
];

// Convertir la información de la petición en una cadena
$requestDataString = json_encode($requestData);

// Calcular el código HMAC
$hmac = Hash::make($requestDataString, array('key' => $secretKey));

// Agregar el código HMAC al encabezado de autorización
$headers = [
    "Authorization" => "HMAC " . $hmac
];

// Enviar la petición con el encabezado de autorización
$response = $client->request('POST', $url, [
    'headers' => $headers,
    'json' => $requestData
]);

En este ejemplo, primero se establece una clave secreta.
Luego, se crea un arreglo con la información de la petición y se convierte en una cadena de texto con json_encode. Finalmente, se utiliza la función hmac() de la clase Hash para calcular el código HMAC utilizando la clave secreta y la información de la petición, y se agrega al encabezado de autorización antes de enviar la petición.

Es importante mencionar que esta clave secreta debe ser compartida entre el cliente y el servidor, y debe ser guardada de manera segura. Ademas la petición debe llegar con un header 'HMAC' con el codigo previamente generado.

En el lado del servidor, se recibiría la petición y se verificaría el HMAC para asegurarse de que la petición no ha sido alterada en tránsito.

?>