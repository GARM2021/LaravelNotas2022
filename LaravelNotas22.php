<?php


20221130
https://laravel.com/docs/9.x/facades

https://desarrolloweb.com/articulos/estructura-carpetas-laravel5.html

// ! Si nosotros tuviésemos que usar una librería que no estuviera en la carpeta vendor la tendríamos que especificar en el archivo composer.json en el campo require. Luego hacer un "composer update" para que la nueva dependencia se instale.

// ? HTTP Request en Laravel -----------------------------------------------
 https://desarrolloweb.com/articulos/http-request-laravel5.html
// *  El hecho de recibir los objetos de los que depende una clase por parámetro es lo que se conoce como inyección.

//? Recibir la solicitud (request) ----------------------------------------------------------

Route::post('recibir', 'PrimerController@recibirPost');

public function recibirPost(Request $request){
    //!en este punto del código $request es mi objeto HTTP Request. (inyectado)
    echo $request->input('id');
    $todos_los_datos = $request->all();
}

//  *Para recuperar la URI actual de una solicitud (lo que va después del dominio), sobre nuestro objeto request invocaremos el método path().

//  *Para recuperar la URL completa de una solicitud (toda la ruta completa, incluyendo el dominio), invocamos el método url().

public function mostrarUriUrl(Request $request){
    echo $request->path();
    echo "<br>";
    echo $request->url();
}


// ? Recibir parámetros de la ruta --------------------------------------------------------------

Route::post('editar/{id}', 'PrimerController@editar');



// *Ahora podremos recibir tanto la request como el parámetro de la ruta {id} en el método de la acción del controlador.

public function editar(Request $request, $id){
	echo "Recibo $id como parámetro de la ruta.";
	echo "Además recibimos estos datos por formulario: " . implode(', ', $request->all());
}


// ? Introducción a modelos en Laravel

// *En el patrón además los modelos mantienen lo que se llama la lógica de negocio, que son las reglas que deben cumplirse para trabajar con los datos.

//? Laravel middleware


// * "HTTP middleware provee un mecanismo adecuado para filtrar solicitudes HTTP entrantes a la aplicación [...] hay diversos middleware incluidos en el framework Laravel, como middleware para mantenimiento, autenticación, protección CSRF mediante token, etc."

// !Archivo Kernel.php, donde se registran los middelware
// ! app/Http/Kernel.php.

EJEMPLO 

php artisan make:middleware DomingoMieddlewar

// * se genera en 

app/Http/Middleware/DomingoMieddlewar

<?php

namespace App\Http\Middleware;

use Closure;

class DomingoMiddleware
{
    public function handle($request, Closure $next)
    {
        if(date('w')==='0'){
            echo "Es domingo!";
        }else{
            echo "No es domingo";
        }
        return $next($request);
    }
}

//* Hay una propiedad de la clase Kernel donde se coloca toda la lista de middleware a ejecutar de manera global;

protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        // otros middleware
        // ...
        \App\Http\Middleware\DomingoMiddleware::class,
    ];


// *    Ahora podemos ejecutar el middleware a través del índice que le hemos dado en el array anterior. Lo veremos hacer mediante tres alternativas distintas:

// * Desde el sistema de routing, al definir la ruta, podemos indicar un middleware que queremos usar. Esto lo tenemos que hacer desde el registro de rutas, realizado en el archivo routes.php, mediante una sintaxis como la que puedes ver a continuación.

Route::get('/test', ['middleware' => 'domingo', function(){
    return 'Probando ruta con middleware';
}]);


// *También desde el sistema de routing podemos generar un grupo de rutas donde se ejecute este middleware.

Route::group(['middleware' => 'domingo'], function(){
    Route::get('/probando/ruta', function(){
        //código a ejecutar cuando se produzca esa ruta y el verbo 
        return 'get';
    });

    Route::post('/probando/ruta', function(){
        //código a ejecutar cuando se produzca esa ruta y el verbo POST
        return 'post';
    });
});


// *  hemos indicado dos rutas donde se ejecutará ese middleware etiquetado como "domingo".

// * Desde un controlador también podemos llamar a un middleware. Lo podemos hacer desde el constructor, por lo que ese middleware afectará a todas las acciones dentro del controlador.

class PrimerController extends Controller
{
    public function __construct(){
        $this->middleware('domingo');
    }
}


// * En este caso no necesitamos mencionar el middleware desde el sistema de rutas, solo lo mencionamos desde el constructor del controlador, para ejecutarse en cualquiera de las acciones declaradas en él.

// ? Responses en Laravel

//? Enviar una vista mediante una instancia Response-----------------------------------

// *Uno de los métodos del objeto Response que te devuelve el helper response() es view(), en el que indicamos la vista que queremos procesar.

Route::get('respuesta6', function(){
	return response()
		->view('error')
		->header('status', 404)
		->header('Refresh', '5; url=/');
});

//! Response se puede utilizar para responder en formato JSON, generar cookies, enviar archivos para descarga, etc.

//? Session Laravel

//*Como decíamos, la principal novedad del sistema de sesiones de Laravel con respecto a PHP nativo es la facilidad con la que podemos alterar el funcionamiento interno de las sesiones, para definir un soporte de almacenamiento diverso. Las opciones principales son las siguientes:

// * file - Las variables de sesión se almacenan en ficheros, sobre la ruta: storage/framework/sessions.
// * cookie - Las variables de sesión son almacenadas en cookies en el navegador, debidamente encriptadas.
// * database - Las sesiones son implementadas en la base de datos. Atención, pues esta configuración requiere que se ejecuten ciertas migraciones para disponer de    las tablas de almacenamiento.
// * memcached / redis - Este soporte permite usar un sistema de caché en memoria, más rápido que otras alternativas.

//? Mecanismos para el acceso al sistema de sesión de Laravel

//? Helper global session

//? Este helper te permite acceder al API para el acceso a la sesión desde cualquier parte de tu código, incluso desde las vistas si fuera necesario. Es la manera más cómoda para acceder a los datos de la sesión, modificarlos, etc.

$identificador_carrito = session('idCarrito');

// ? Una instancia de Request

//* A partir del objeto Request de Laravel podemos también acceder al API de las sesiones. Esta alternativa requiere por tanto disponer de un objeto $request, del que ya hemos hablado en otras ocasiones en el Manual de Laravel. Este objeto por ejemplo puede venir inyectado en un método de un controlador.

public function index(Request $request) {
    $identificador_carrito = $request->session()->get('idCarrito');

    // Resto del código de tu controlador...
}

//? Crear y recuperar variables de sesión con Laravel

//? Almacenar una variable de sesión

//* Simplemente le indicamos en un array asociativo qué variables de sesión deseamos almacenar. Este array contiene pares clave/valor. La clave indica el nombre de la variable y el valor, su valor.

session(['idCarrito' => '123456']);

//? Recuperar una variable de sesión

//*El helper nos permite de una manera muy cómoda recuperar un dato almacenado en la sesión, indicando simplemente qué nombre de variable deseamos acceder.

$valor_almacenado = session('idCarrito');

//? Borrar una variable de 

//*Mediante el objeto de session podemos decirle a Laravel que borre una variable de sesión. Usamos para ello el método forget(), de este modo:

session()->forget('orderId');

//? Comprobar la existencia de una variable de sesión

//* A veces es necesario saber si existe en el sistema de sesiones de Laravel una variable determinada. Para ello usamos el método has().

session()->has('paymentIntentId');

//? Regenerar el identificador de sesión

// *El identificador de la sesión permite a Laravel, y a nosotros mismos, identificar una sesión de un usuario en la página.

//* Cada sesión tiene su identificador, que se mantiene a lo largo de toda la visita. Sin embargo, cuando un usuario se loguea en el sistema, con sus datos de usuario, este identificador se regenera automáticamente.

//* Esta es una funcionalidad predeterminada en Laravel, pero si por cualquier motivo necesitas regenerarlo de manera manual, también puedes hacerlo a partir del método regenerate().

session()->regenerate();

//? Borrar los datos de sesión al hacer logout

// * Aunque al hacer un logout podríamos suponer que los datos de la sesión deberían borrarse, este no es un comportamiento predeterminado en Laravel, al menos no en todas las versiones.

// * Si no queremos modificar a mano el sistema de login ya implementado por Laravel, podemos realizar una sencilla operativa, que consiste básicamente en generar un manejador de evento para "Logout".

// * Esto se declara en el archivo app/Providers/EventServiceProvider.php mediante el array $listeners.

protected $listen = [
        'Illuminate\Auth\Events\Logout' => [
            'App\Listeners\DeleteSessionData',
        ],
];

// *Mediante el código anterior estamos indicando que cuando se dispare el evento "Logout", se invoque el manejador DeleteSessionData.

// *Ese manejador se puede auto-generar gracias a un comando de Artisan. Ese comando de generación lo tienes que ejecutar después de haber editado el EventServiceProvider.php y se encarga de crear el scaffolding de todas las clases necesarias que se estén usando para gestionar eventos.

php artisan event:generate

// *Nota: Al comando anterior no le indicas nada más. Es decir, no se tiene que especificar qué manejadores o eventos se deben generar, ya que lo deduce del archivo EventServiceProvider.php. Por supuesto artisan dejará sin tocar aquellas clases que ya se hayan creado anteriormente.
// *La clase DeleteSessionData, una vez generada y editada, tendrá un código como este:

<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Lib\StripeHelper;

class DeleteSessionData
{

    public function __construct()
    {
        //
    }

    public function handle(Logout $event)
    {
        session()->forget('variable_de_sesion_que_quieres_asegurarte_de_borrar');
    }
}

?>