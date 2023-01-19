<?php


20221130
https://laravel.com/dofacadescs/9.x/

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

//* Este helper te permite acceder al API para el acceso a la sesión desde cualquier parte de tu código, incluso desde las vistas si fuera necesario. Es la manera más cómoda para acceder a los datos de la sesión, modificarlos, etc.

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

//? Recibiendo datos en Laravel

// ! Como ya sabemos, cuando se envían datos por get, viajan en variables dentro de la URL y cuando se envían por post, viajan en las cabeceras del HTTP, de manera invisible para el usuario.

// ! Nota: Recuerda que para probar este ejercicio lo más cómodo será usar una extensión como Postman, que permite indicar la URL y definir el verbo del HTTP e incluso mandarle datos por formulario. Esto lo explicamos en el artículo Verbos en las rutas de Laravel. También se mencionó en ese artículo que para que los envíos por post nos funcionen habrá que desactivar el middleware de protección CSRF "VerifyCsrfToken".


//? Recibiendo datos en Laravel

//* EJEMPLO

Route::match(['get', 'post'], 'input', 'TestRequestController@recibir');
  

TestRequestController.php 
class TestRequestController extends Controller
{
     public function recibir(Request $request)
    {
        // código para esta acción del controlador
    }
}

// ? Averiguar el método en una solicitud

$metodo = $request->method();

// *Si en cualquier momento queremos preguntar si estamos ante un método en particular podemos preguntarlo con isMethod().

if($request->isMethod('post')){
    echo "Estoy recibiendo por post";
}

//? Recuperando datos enviados por GET / POST

//* Ahora vamos a ver una serie de métodos de Request que nos sirven para traernos las variables enviadas por GET o POST.

//* Obtener un dato suelto: método input(), indicando la cadena que queremos recibir.

$nombre = $request->input("nombre");

//* Valor predeterninado: Ahora también podemos especificar un valor predeterminado para recibir como respuesta en la consulta, si es que ese dato no fue recibido en el Request.

$edad = $request->input("edad", 18);

//* Recibir datos que vienen en arrays: Como debes saber, es posible agrupar en arrays datos que se envían por formularios o por la URL. Hay diversos modos, el más habitual es ponerle el name del campo input un valor con unos corchetes vacíos al final.

Categoria0: <input name="categorias[]">

//* Otra opción es que te vengan los datos de un SELECT "multiple".

<select name="categorias" multiple>
...
</select>

//* Todas esas alternativas las construyes con el HTML, no vamos a seguir dando ejemplos. Ahora, si lo deseas, con Laravel lo podrías recoger con:

$categorias = $request->input("categorias");

//* Así recibes un array con todas las categorías, pero podrías acceder a alguna de las posiciones del array en concreto, mediante la notación "punto".

$categorias = $request->input("categorias.1.voto");

//* Saber si se recibe un dato: Puedes saber si estás recibiendo un dato en concreto en el conjunto de los datos recibidos con HTTP, mediante el método has().

if ($request->has("edad")){
    echo "Encontré la edad";
}

//* Recibir un grupo de datos: otra posibilidad, cuando quieres recibir varios datos a la vez, es usar el método only(), en el que indicas un listado de las claves (key) de aquellos datos que deseas recibir. Lo que te devuelve only() es un array asociativo con pares llave/valor de aquellos elementos indicados.

$datos_grupo = $request->only('nombre', 'id');

//* Mediante el anterior código recibirás un array asociativo con la llave "nombre" e "id". Sus valores serán aquellos valores que se reciban en Request. Si alguno de ellos se ha especificado pero no se ha recibido, entonces simplemente recibirás null como valor.

//*  Recibir todos los datos menos algunos: Es parecido al anterior método, pero en vez de indicar qué queremos recibir, se indica qué se quiere excluir.

$datos_excepto = $request->except('categorias');

//* Recibir todos los datos del Request: Este método ya lo vimos anteriormente, simplemente nos devuelve un array asociativo con la totalidad de los datos que se encuentren en la solicitud.

$todos = $request->all();

//* Recibir un campo de tipo file: Si estamos haciendo upload de archivos a través de un formulario, los campos los tenemos que recoger de una manera diferente, usando el método file().

$archivo = $request->file('archivo');

//*  También podemos comprobar si existe el archivo usando hasFile().

if($request->hasFile('archivo')){
    $archivo = $request->file('archivo');
}


//? Volcado de la entrada de datos de usuario a la sesión  https://desarrolloweb.com/articulos/volcado-entrada-datos-usuario-sistema.html

//? Old Input

//*En Laravel 5 los datos enviados a una solicitud se pueden leer en esa solicitud y en la siguiente, si realizamos el correspondiente volcado a la sesión. Es una de las funcionalidades que nos ofrece el objeto Request.

//* En la mayoría de los casos esto es algo que Laravel hace de manera automática. Es decir, en un proceso de validación, si utilizamos el sistema de validación integrado en Laravel, el flash de los datos de Request a la sesión se hace sin que el desarrollador tenga que intervenir, pero además nosotros podemos hacerlo explícitamente, si no validamos con las herramientas del framework o si nos viene bien para cualquier otro tipo de situación.

//* El método que nos permite volcar los datos de HTTP Request a la sesión se llama flash(). Lo hacemos sobre el objeto $request que ya como costumbre venimos inyectando en el controlador.

$request->flash();

//* Nota: También como alternativa puedes flashear solo unos campos o bien todos menos aquellos que se indiquen.

$request->flashOnly('nombre', 'edad');
$request->flashExcept('clave');

//* Luego querrás redirigir hacia una nueva ruta. Esta es una operación que depende del sistema de respuesta (response) y que se puede hacer de varias maneras. Comúnmente harás algo como:

return redirect('/uri/a/la/que/redirijo');

//* Como generalmente estas dos operaciones, el flasheo y la redirección se suelen hacer juntas, Laravel nos ofrece métodos para poder hacerlas de una sola vez.

return redirect('uri')->withInput();

//* Si no indicas nada como parámetro a withInput(), simplemente se mandan todos los datos del Request. Aunque también puedes flashear solo una porción de la Request, simplemente indicando aquellos campos que quieres que se memoricen en la siguiente solicitud.

return redirect('redirijo')->withInput($request->except("nombre"));

Nota: Recuerda que el método except() devuelve todos los datos del Request menos aquellos campos que se indiquen.

//? Recuperar los datos de la solicitud antigua

//* En aquella solicitud donde rediriges y para la cual has querido memorizar la Request puedes recuperar la información de varias maneras. Solo un detalle, los datos del usuario ya no van a estar en la HTTP Request, sino en la sesión. Sin embargo, en ningún caso necesitaremos acceder nosotros directamente a la sesión.

//* Laravel ofrece distintos métodos para acceder a esa información. Uno de ellos a través del objeto Request, aunque no desde los métodos que ya conocemos como input(), except(), all(), etc. (donde se encuentran los datos de la solicitud normalmente), sino a través del método old().

$request->old('nombre');

//* Además Laravel ofrece una función global llamada old() a la que tenemos que indicar como parámetro el campo que queremos recuperar y nos devuelve su valor.

old('nombre');

//* Al ser un helper global podrás usarlo dentro de una vista sin necesidad de enviarte ningún dato. Lo podrás volcar en un campo input o en cualquier lugar donde lo necesites.

<input type="text" name="nombre" value="<?=old("nombre")?>">

//*Si estás en un template de Blade puedes usar la sintaxis de las dos llaves, que no hemos visto todavía, pero te quedaría como esto.

<input type="text" name="nombre" value="{{old('nombre')}}">

//*La función old() además tiene un segundo parámetro opcional que permite indicar el valor por defecto en caso que no exista ese campo entre los elementos flasheados en la sesión.

old("nombre", "Anonimo");

//* Dada la invocación anterior, si no hay datos flasheados en la sesión, o no existe el campo "nombre" entre los elementos existentes, old() devolverá el valor "Anónimo".


//? Validaciones con Laravel

//? Trait ValidatesRequests en el controlador base

//* Laravel 5 incluye un trait que se carga en el controlador base (clase Controller), llamado "ValidatesRequests". Ese trait contiene código que estará disponible en todos los controladores que nosotros creemos, puesto que todos extienden la clase Controller.

//* Nota: Puedes encontrar el código del controlador base (clase Controller) en la ruta "app/Http/Controllers/Controller.php" y encontrarás la declaración de uso del trait en la línea:

use DispatchesJobs, ValidatesRequests;

//*No confundas ese "use" que es la primera línea de código de una clase, con un "use" que aparece en cualquier lugar del código para definir que estás usando cosas declaradas en otros namespaces.

//* Dentro trait "ValidatesRequests" hay un método que nos sirve para hacer validaciones de los datos que nos llegan mediante HTTP Request, llamado validate(). Por tanto, en todos nuestros controladores podremos invocar este método para realizar validaciones de datos de entrada.

//? El método validate() recibe dos parámetros:

//! El objeto Request
//! Las reglas de validación que queramos definir.

//*El objeto Request ya lo conocemos de sobra, puesto que ya lo hemos tratado muchas veces. Lo inyectarás en el método del controlador como ya hemos detallado en el artículo HTTP Request en Laravel 5.

//* Las reglas de validación son diversas y perfectamente configurables por nosotros mediante una sencilla sintaxis. De momento vamos a conocer algunas reglas elementales, pero Laravel tiene muchas otras que trataremos más adelante. Aunque este tema lo puedes consultar de una manera muy rápida en la documentación oficial de Laravel 5.1 en la sección Available Validation Rules.

//* Para especificar las reglas, como veremos en el código del método store(), un poco más abajo, las indicamos con un array asociativo. Como llaves (key) del array usamos el nombre del campo que deseamos validar (atributo "name" de los campos de formulario) y como valores indicamos todas las reglas que se deben comprobar en dicho campo para considerarlo correcto.

//* Este sería el código de nuestro método store() del controlador, donde puedes ver la llamada a validate() para la validación del formulario de producto que has debido de crear en la anterior acción.

public function store(Request $request)
{
    $this->validate($request, [
        'nombre' => 'required|max:255',
        'descripcion' => 'required',
        'precio' => 'required|numeric',
    ]);

    echo 'Ahora sé que los datos están validados. Puedo insertar en la base de datos';
}

// * Los errores de validación los vamos a extraer de una variable llamada $errors, que está disponible en toda vista.

// * Laravel automáticamente crea la variable $errors flasheando en la sesión todos los errores de validación que se hayan podido producir. Si no hubo tal error de validación Laravel crea igualmente esa variable, pero vacía, por lo que podemos usarla siempre que queramos, sin preocuparnos si está o no está definida, porque siempre lo va a estar.

// * Además el bindeo se realiza automáticamente a la vista, por lo que nosotros no tenemos que hacer absolutamente nada para pasar esa variable.

// * Por tanto, en nuestra VISTA podemos fácilmente recorrer los errores definidos en $errors y mostrarlos en el formato que nosotros deseemos. A continuación tendrías un pedazo de código que se encarga de recorrer esos errores, mostrando un elemento DIV con los errores en el caso que haya alguno, y listando cada uno en un elemento LI de una lista UL.

@if(count($errors) > 0)
	<div class="errors">
		<ul>
		@foreach($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
		</ul>
	</div>
@endif

// * De momento este código te puede parecer un poco extraño, porque no hemos hablado de la sintaxis del sistema de templates Blade, el oficial de Laravel. No te preocupes porque lo tocaremos más adelante y podrás entenderlo perfectamente. No obstante, para que te funcione, es muy importante que nombres el archivo de esta vista con extensión ".blade.php", porque si no, el código "Blade" no se ejecutaría.

// *Poblar de nuevo los valores de los campos de formulario
// *Primero decir que esta otra parte del trabajo en la vista realmente nos la podríamos ahorrar en Laravel, ya que hay un método muy sencillo por el que Laravel nos lo hace automáticamente. Se trataría de usar la librería que viene con Laravel para generar código de los formularios, pero como no la hemos visto todavía, nos toca hacerlo "a mano", aunque comprobarás que tampoco es nada doloroso.

// *Simplemente en los campos INPUT hemos creado el value, asignando lo que nos devuelve el helper old(), indicando el campo que queremos recuperar. Esa función se encarga de traerse aquello que estaba escrito anteriormente en el campo del formulario, tal como explicamos en el artículo Volcado de la entrada de datos de usuario a la sesión.

<input type="text" name="nombre" value="{{old('nombre')}}">

// *Este código no necesita muchas más explicaciones, pero simplemente tómalo como una alternativa, sabiendo que hay mejores maneras de hacer esto, si le dejas a Laravel la responsabilidad de crear el HTML de tu formulario.

//* Código completo de la vista
// *Como prometí, aquí va el listado completo de la vista que he usado para este ejercicio.

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Crear un producto</title>
	<style>
	.errors{
		background-color: #fcc;
		border: 1px solid #966;
	}
	form{
		margin-top: 20px;
		line-height: 1.5em;
	}
	label{
		display: inline-block;
		width: 120px;
	}
	</style>
</head>
<body>
	<h1>Crear un producto</h1>
	@if(count($errors) > 0)
		<div class="errors">
			<ul>
			@foreach($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
			</ul>
		</div>
	@endif

	<form action="/producto" method="post">
		<label for="nombre">Nombre:</label> <input type="text" name="nombre" value="{{old('nombre')}}">
		<br />
		<label for="descripcion">Descripción:</label> <input type="text" name="descripcion" value="{{old('descripcion')}}">
		<br />
		<label for="precio">Precio:</label> <input type="text" name="precio" value="{{old('precio')}}">
		<br />
		<input type="submit" value="Crear">
	</form>
</body>
</html>
// *Por favor, ten en cuenta que esta vista tiene un código muy elemental, simplemente para salir del paso. Observa estos detalles:

//* No estoy pasando el token para protección CSRF, por lo que tendrás que desactivar el middleware asociado. Esto lo hemos explicado ya en varias ocasiones, así que debes saber a qué me refiero. Si no, revisa el artículo Verbos en las rutas de Laravel.
//* Guarda el archivo en la carpeta correspondiente. Tal como quedó el código del método create() del controlador, sería en la carpeta "resources/views/producto".
//* El nombre del archivo será create.blade.php. Ese nombre lo marcamos al invocar la vista, desde el controlador ProductoController, en la acción create().


// ?Validación reutilizable por Requests en Laravel 5

//* La validación por Requests de Laravel 5 es un estilo de validación más avanzado que puede ser reutilizable y nos libera a los controladores de las operaciones de comprobación de la entrada de usuario.

//* En el artículo anterior del Manual de Laravel estuvimos estudiando una primera vía para construir sistemas de validación de la entrada de usuario. Observamos que es bastante potente, ya que la mayoría de las acciones típicas durante la validación las hace el framework directamente, por lo que nuestro código se queda muy sencillo.

//* No obstante, todavía puede mejorar la situación. En este artículo explicamos las validaciones por el mecanismo de extender la Request, creando Request especializadas que son capaces no solo de entregar la información del usuario, sino también de validarla convenientemente.

//* No confundir "Request" en singular con "Requests" en plural. La primera es la que hemos venido usando a lo largo de todo el curso de Laravel, que nos ha proporcionado diversas informaciones y funcionalidades sobre la solicitud del cliente. Esa Request, en singular, siempre está presente en este framework PHP como parte de los recursos que te entregan para realizar tus aplicaciones. Pero por otra parte, nosotros como desarrolladores, podemos crear un número indeterminado de Requests, en plural, para aquellas variantes de entrada de datos que necesite nuestra aplicación.

//* Las Requests que podemos crear, al extender la Request, disponen de la misma funcionalidad que ya conocemos. Pero además, en esas clases Request que crearemos a partir de ahora vamos a incluir código necesario para las validaciones específicas de ese tipo de entrada. Por ejemplo, una de las Requests podrá ser para validar los datos del usuario, otra para validar datos de un producto, un post, o lo que sea necesario.



//* Ventajas de la validación por Requests
//* Son muchos los puntos que mejora este sistema de validación, pero básicamente debemos señalar:

//* Separación del código. Es la base de muchos patrones de diseño y nos permite mantener clases más simples, más desacopladas y por tanto de más fácil mantenimiento.
//* Reutilización, al poder usar esa misma request en cualquier parte donde estemos recibiendo ese tipo de entrada de usuario. Por ejemplo, cada vez que reciba datos de un usuario podré usar la request del usuario.
//* Cómo crear una Request personalizada
//* Existe una carpeta específica para guardar las Requests que creemos para nuestro proyecto, en la ruta "app/Http/Requests".

//* Además existe un comando de artisan "make:request" que nos hace el trabajo de crear el código base de estas clases para validar una solicitud dada.

//* php artisan make:request ProductoRequest
//* Con esto obtendremos una clase llamada ProductoRequest que se sitúa en la ruta de las requests. Su ruta completa por tanto será: "app/Http/Requests/ProductoRequest.php". Si abres este archivo comprobarás que tiene un par de métodos:

class ProductoRequest extends Request
{
    public function authorize()
    {
        return false;
    }

   public function rules()
    {
        return [
             //
        ];
    }
}

//* Ambos métodos son para realizar validaciones de la información, pero su responsabilidad es de distinto tipo:

// *En la parte de authorize() se comprobará si el usuario está autorizado a realizar esa request. Por ejemplo podrás comprobar si el usuario tiene los permisos adecuados, si aquello que intenta modificar le pertenece y cosas así. Si te fijas, cuando creas el Requests se devuelve un return false, y eso querrá decir que no se le deja pasar.

//* En la parte de rules() indicarás las reglas que deben de cumplirse para que se considere que los datos son válidos. Si los campos existen y tienen los valores que se esperan, numéricos, cadenas, de unas dimensiones, etc. Estas reglas de validación son las mismas con las que trabajamos en el anterior artículo sobre validaciones.
//* Laravel primero hará la comprobación de autorize y luego verá si cumple las reglas que se han indicado en rules().

// *A continuación tienes el listado de una Requests que hemos creado para el formulario de producto que usamos en el artículo anterior.

<?php
namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Http\Requests\Requests;

class ProductoRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nombre' => 'required|max:255',
            'descripcion' => 'required|min:10',
            'precio' => 'required|numeric',
        ];
    }
}

//* Como puedes ver, en authorize() hemos colocado de momento un return true;. Esto producirá que siempre se autorice a realizar esta acción. Más adelante escribiremos alguna cosa en este método.

//* Por su parte, en rules() tenemos las mismas reglas de validación que ya conocemos, en el array que se devuelve.

// ?Cómo usar una de nuestras Requests personales

//* Después de crear una requests podemos usarla en el controlador. Solo necesitamos realizar dos tareas:

//* Primero agregar el "use" correspondiente para que se conozca el nombre de la clase ProductoRequest.

use App\Http\Requests\ProductoRequest;


Luego podremos usar esa clase en la cabecera de los métodos que deban realizar la validación de la entrada de datos.

public function store(ProductoRequest $request)
{
    echo 'Estoy usando la Request personalizada ProductoRequest. ';
    echo 'No necesito invocar explícitamente las funciones para validar. ';
    echo 'Si estoy aquí sé que los datos están validados.';
    dd($request->all());
}

//* Como puedes ver, al definir el método store() colocamos el parámetro que ahora se especifica con el nombre de nuestra request personalizada: ProductoRequest.

//* Laravel ya se encarga por su cuenta de llamar a los procedimientos de validación automáticamente, sin que tengamos que intervenir. Si no pasa la validación realizará una de estas dos acciones:

//* Si no pasa el authorize() mostrará un "Forbidden". Este tipo de error se supone que será por una acción no permitida a nivel de aplicación y teóricamente porque están queriendo hacer alguna cosa, burlando la seguridad. Por ello no se realiza un tratamiento especial, mostrando simplemente un feo mensaje que no da muchas pistas al usuario de lo que pueda estar pasando.
//* Si no pasa el rules() hace lo mismo que el método validate cuando no se pasan las reglas, es decir, redirige a la página anterior flasheando la entrada de datos a la sesión y generando los errores de validación para que luego podamos mostrarlos, bien claritos para ayudar al usuario a localizar los problemas en la entrada de datos. Para más aclaraciones consulta el anterior artículo de introducción a las validaciones.
//* Luego, cualquier código dentro del método se ejecutará solamente si las validaciones pasaron correctamente. Así que el método del controlador nos queda liberado de cualquier proceso de validación y podemos escribir solo el código de los procesos necesarios para ejecutar las acciones deseadas.

//* Además, dentro del método del controlador podemos usar tranquilamente el objeto de la clase ProductoRequest como si fuera un objeto de la clase Request normal, ya que la clase ProductoRequest hereda de Request. Podremos acceder a los datos de usuario y a todas las otras operaciones que hay en un Request de las que ya conocemos.

//* En todos los controladores que necesitemos validar la entrada de datos podremos hacer el mismo procedimiento para validar implícitamente, usando el Requests creado para el caso. Por tanto, no es necesario repetir el código de validaciones en varias partes de la aplicación y se produce la reutilización.

//? Qué sentido tienen las validaciones en el controller

//* Si has apreciado lo limpios que quedan nuestros controladores con este estilo de validación y valoras positivamente la separación del código y la reutilización de las funciones de validación, quizás te preguntarás ¿Y entonces para qué necesito, o qué sentido tienen, las validaciones en el controller que vimos en el artículo anterior?

//* Bueno, la verdad es que continúan siendo útiles en varios casos, primero y menos importante es que en determinado momento puede que nos resulte más cómodo realizar una validación sencilla de alguna cosa en el controlador, sin que tengamos que generar una nueva clase para realizar algo rápidamente.

//* Pero la razón de más peso es que hay acciones que pueden requerir validaciones más especializadas. Por ejemplo al crear una factura podemos validar una serie de informaciones básicas. Pero quizás al editarla hay otra serie de reglas que se deben comprobar. En ese caso, esas validaciones específicas tenemos que colocarlas en la acción del controlador que las necesita.

//* De momento es todo, esperamos que aprecieis la potencia y simplicidad de este nuevo método de validar y lo podáis practicar.


//? Cómo funcionan las Cookies

// *Antes de explicar los mecanismos que nos ofrece Laravel creo que unas pequeñas notas sobre cómo funcionan las cookies aclararán muchas dudas. Como no es el objetivo de este artículo hablar de cookies en general, lo voy a esquematizar en cuatro puntos:

//* La creación de cookies desde el servidor, en lenguajes como PHP, se debe producir en la respuesta de una solicitud HTTP (Response). Osea, solo cuando devuelvo los datos de una solicitud al servidor soy capaz de escribir una cookie en el navegador.

// *La lectura de cookies se realiza a través de la petición. Si el navegador del usuario tiene alguna cookie almacenada en el sistema, llega al servidor en la solicitud emitida por el cliente web (Request).

// * cookies viajan en las cabeceras del protocolo HTTP, de manera transparente para el usuario. La información para la creación o actualización de cookies se escribe dentro de las cabeceras del HTTP de respuesta. La información para la lectura de las cookies viaja al servidor en las cabeceras HTTP de la solicitud.

//* Por todo ello, cuando proceso una página en el servidor que escribe una cookie, esa cookie solo estará presente para lectura en la siguiente solicitud que se realice al servidor.


// ?Cookies en Laravel

// * comprenderás según lo anterior, las cookies forman parte de dos sistemas de Laravel, el de Request para acceder a las cookies creadas, así como el de Response para almacenar nuevas o actualizar antiguas cookies.

// *Además es interesante saber que todas las cookies gestionadas por Laravel están encriptadas y firmadas con un código de autenticación, de eso modo podemos asegurar que no han sido modificadas a mano por el usuario.

//? Crear cookies en el navegador con Laravel
//*Las cookies que creamos desde Laravel son instancias de una clase de Symfony: Symfony\Component\HttpFoundation\Cookie. Para crearlas Laravel ofrece diversos mecanismos que veremos. El más sencillo y directo es un helper llamado cookie() que realiza la tarea por nosotros.

// *Los parámetros básicos del helper cookie(), que querrás utilizar al crear la mayoría de cookies, serán el nombre de la cookie, su valor y el tiempo de persistencia (en este caso expresado en minutos).

$nueva_cookie = cookie('nombre', 'valor', $minutos);

//* *Si queremos que la cookie se recuerde por el mayor tiempo posible (lo máximo son 5 años) puedes usar esta otra alternativa de código.

$nueva_cookie = cookie()->forever('micookie', 'mivalor');

//* Con eso obtengo el objeto de la clase Cookie, pero todavía me falta enviarlo al navegador, algo que veremos enseguida.

//* Además también puedes crear cookies con otra serie de parámetros, opcionales, que listamos a continuación en el orden en el que tienen que ser entregados. Como verás, son prácticamente los mismos parámetros que para hacer cookies en PHP "nativo".

// ! Parametros de las cookies 

Nombre de la cookie (string)
Valor (string)
Minutos de persistencia (entero)
Camino/ruta donde va a estar disponible (string)
Dominio o subdominio de existencia (string)
Si es segura, https (boleano)
Si es solo insegura, no https (boleano)

Por defecto están disponibles para el subdominio donde se creó, y son sólo para http normal. Enviar una Cookie al navegador en el sistema de "Response"

Para enviar una Cookie al navegador se debe adjuntar a la respuesta. Esto lo tienes que hacer desde el sistema de Response, para lo que necesitas un objeto de la clase Response.

Nota: Los objetos de la clase Response los obtienes de varias maneras y es algo que ya explicamos en el artículo sobre Responses en Laravel 5.
La clase Response tiene un método llamado withCookie() que adjunta un objeto Cookie en la respuesta de la solicitud HTTP. Así Laravel, al construir la respuesta que enviará al navegador será capaz de enviarle también la cookie adjuntada.

$response = response("Voy a enviarte una cookie");
$response->withCookie($nueva_cookie);
Aquí estamos usando el helper response() para obtener el objeto de la clase Response de Laravel, luego estamos enviando en la respuesta la cookie $nueva_cookie. Obviamente, $nueva_cookie es una referencia a un objeto Cookie como los que acabamos de enseñar a crear.

Ahora puedes ver un sencillo ejemplo de creación de una cookie, procedimiento que hemos colocado en una closure del sistema routing, para facilitar las cosas.

Route::get('pruebacookie', function(){
	$nueva_cookie = cookie('probando', 'valorprobando', 60);
	$response = response("Voy a enviarte una cookie");
	$response->withCookie($nueva_cookie);
	return $response;
});
Lectura de una Cookie mediante el sistema de request
Ahora veamos cómo leer una cookie ya creada. Es algo que hacemos a través del sistema de Request. Podríamos leerla de varias maneras:

A partir de la "facade" (fachada) de Request.

Request::cookie('nombre_de_la_cookie');


Nota: Recuerda hacer el "use Request;" para tener acceso a esta fachada. Otra alternativa sería anteponer una contrabarra en Request para que Laravel sepa que te estás refiriendo al namespace global.


\Request::cookie('nombre_de_la_cookie');


Como alternativa, si hemos inyectado el correspondiente objeto de la clase Request en el método del controlador, tenemos acceso al método cookie() a partir de ese objeto:

public function personalizar(Request $request){
	$cookie_leida = $request->cookie('nombre');
	// resto del código del controlador
}
Si la cookie que intentamos recibir no estaba en el navegador del usuario, este método devolverá null como valor de la cookie. Para evitarnos tener que comprobar si el valor es null y en ese caso establecer un valor predeterminado, podemos llamar al método cookie() indicando como segundo parámetro el valor a cargar por "default" (en caso que no exista la cookie).

Request::cookie('cookie_lectura', "valor_x_default");
Dejar cookies en la cola de escritura
Ahora vamos a hablar de otro mecanismo interesante de trabajo con Cookies que nos relatan en la documentación de Laravel 5.0.

Nota: Es importante señalar que este mecanismo de encolado de la cookie ha sido borrado de la documentación de Laravel 5.1. Quizás lo han recolocado en algún otro lugar o quizás piensan eliminarlo del framework. Sin embargo, el código está probado y sigue funcionando perfectamente en la versión 5.1.
Puede darse el caso que quieras generar una cookie y todavía no se haya construido el objeto response y por cualquier motivo no lo desee hacer en este momento. En ese caso puedo dejar las cookies encoladas para su escritura en la siguiente respuesta (Response) que se genere.

Eso se consigue fácilmente con la fachada Cookie y el método estático queue().

Cookie::queue($una_cookie_a_encolar);

Nota: Para que Cookie esté disponible recuerda hacer el "use Cookie;". O bien la alternativa de comenzar con una contrabarra, para indicar que esta clase se encuentra en el namespace global.

\Cookie::queue(\Cookie::make('cuco', 'cucurucu', 60 * 24 * 365));

Redirigir con envío de cookie
En ocasiones podemos necesitar crear una cookie en un response que no muestre contenido, sino que redirija al usuario a otra página. Eso es perfectamente posible en Laravel, solo que no usarás un objeto response tal cual como hemos aprendido anteriormente, sino que lo harás con una instancia de un objeto "redirector".

El siguiente código crea una ruta que redirige a otra. En la redirección se añade una cookie usando el mismo método withCookie() que hemos invocado sobre un objeto Response, solo que ahora lo invocamos sobre el objeto Redirector que nos devuelve el helper redirect().

Route::get('redirigecookie', function(){
	return redirect('/otra/ruta/a/redirigir')->withCookie(cookie('redir', '1234', 20));
});

En este caso, como la cookie se genera en la Response que realiza la redirección, cuando el usuario accede a /otra/ruta/a/redirigir la cookie ya está disponible en el sistema.

Nota: No hemos hablado todavía en profundidad de redirecciones, pero lo haremos en breve. De todos modos, la cosa es bastante sencilla, si te apoyas en el helper redirect(), como habrás podido apreciar.
En el siguiente artículo vamos a seguir trabajando con Cookies pero de manera más práctica. Realizaremos un ejemplo completo en Laravel en el que haremos diversas cosas que nos ayudarán a repasar todo lo aprendido.


//? Ejemplo completo de uso de cookies en Laravel

Ejercicio práctico para ilustrar el uso de cookies en el framework PHP Laravel, así como de request y response, controladores, acciones, rutas, etc.

En este ejemplo completo de uso de cookies vamos a practicar con muchas de las cosas que hemos visto hasta el momento en el manual de Laravel 5. Nos servirá para conocer mejor las cookies, pero también para realizar rutas, controladores, vistas y formularios, recibir datos, validar campos, etc.

En realidad el ejemplo es bien sencillo, ya que lo interesante es practicar con el flujo de trabajo con el que construir aplicaciones en Laravel, no tanto complicarse con formularios llenos de datos que recibir. Sin embargo, con lo que vamos a ver podremos construir muchos tipos de formularios ya personalizados con nuestras preferencias, con los datos que necesitemos recibir. Así que ahí vamos!



Objetivo: Sistema de personalización de aspecto
El objetivo es hacer un sistema de personalización del tamaño de la fuente en un supuesto sitio web.

Básicamente tenemos un formulario donde el usuario puede seleccionar el tamaño de fuente que prefiere para visualizar el sitio. Una vez seleccionado el tamaño de la fuente se envía el formulario y se guardará el dato en una cookie, de modo que en siguientes accesos el sitio web sea capaz de memorizar la preferencia del usuario.

//? Rutas de la aplicación

Tendremos dos rutas simplemente, una que muestra el formulario de personalización y otra que recibe el dato y si es correcto se encarga de almacenar la cookie.

Route::get('personalizacion', 'PersonalizacionController@personalizar');

Route::post('personalizacion', 'PersonalizacionController@guardarpersonalizacion');

Fíjate que la URI es la misma, pero cambian los verbos HTTP. La primera se activa cuando no se envían datos por formulario (verbo get) y la segunda cuando sí se reciben datos de formulario (verbo post). Como ves, ambas invocan al mismo controlador, con diferentes acciones.

Tienes más información de las rutas y dónde se coloca este código en el artículo sobre rutas de Laravel.

//?  Controlador PersonalizacionController

Nuestro controlador se llama PersonalizacionController, como sabes se puede crear la base del controlador inicial con el comando artisan. Eso lo aprendimos ya en el artículo de los controladores en Laravel. Así que nos vamos a limitar a colocar el código de las acciones que tenemos que crear dentro.

Comenzamos con la acción "personalizar" que es la que se invoca cuando se usa el verbo HTTP get. En esta acción vamos a tener que mostrar el formulario para personalizar, que permite al usuario escoger el tamaño de la fuente.

public function personalizar(){
	$tamano_fuente = \Request::cookie('fuente', '16pt');

	return view('personalizacion.formulario', [
		'fuente' => $tamano_fuente
	]);
}
Esta primera acción realmente solo llama a una vista. Lo que pasa es que la vista requiere un dato que es la fuente con la que queremos personalizar el sitio web, por lo tanto tenemos que pasárselo. Para recuperar el dato necesitamos acceder a la cookie de personalización. La primera vez que accede el usuario a la web esa cookie no estará creada, por lo que en ese caso tendremos que darle un valor por defecto a la fuente del sitio web.

Por tanto, en la primera línea del método accedemos al valor de la cookie a través de la fachada de Request. En la llamada al método cookie() enviamos el nombre de la cookie que queremos leer y el valor predeterminado en caso que esa cookie no exista.

Nota: Recuerda que estuvimos explicando todas estas cosas sobre cookies en el artículo cookies en Laravel 5.
Luego mostramos la vista del formulario, enviando el dato que necesita la vista para mostrarse con la fuente personalizada.

La segunda acción es la que se procesa cuando se recibe alguna cosa del formulario de personalización. Como podrás apreciar en el siguiente código, hacemos una validación de la información que necesitamos para trabajar y luego nos encargamos de crear la cookie. Usamos el sistema de Response, pero en esta ocasión nos basamos en una redirección. Esto es así porque realmente cuando recibimos la personalización no queremos mostrar ningún mensaje en concreto, solo querremos devolver al usuario a la página anterior y en esa página se podrá apreciar la nueva fuente configurada para el sitio. El código es un poco más complicado que el del método anterior, pero seguramente lo podrás entender.

public function guardarpersonalizacion(Request $request){
	$this->validate($request, [
		'fuente' => 'required'
	]);
	return redirect('/personalizacion')
		->withCookie(cookie('fuente', $request->input('fuente'), 60 * 24 * 365));
}
El sistema de validación en este caso solo comprueba el campo de formulario "fuente". Ese campo es requerido. Sobre este punto encuentras más explicaciones en el artículo de Validaciones en Laravel.

Nota: Hay que recordar que, según el código del controlador y gracias a la validación "required" del campo fuente, el código que hay a continuación de la llamada al método validate() solo se ejecutará cuando realmente se reciba la fuente a cambiar en el sistema de personalización.
Como habrás podido apreciar, se usa el helper redirect(), que explicaremos con detalle en un futuro artículo. Ese helper nos devuelve un objeto response especial llamado "Redirector", a partir del cual se crea la Cookie (pues las cookies solo se pueden crear cuando se devuelven datos al cliente en el response).

Al objeto Redirector le indicamos que se genere la cookie donde se almacena la fuente. Esa cookie tiene como nombre "fuente", como valor el dato recibido en el input y como duración 1 año expresado en minutos.

Nota: Sobre Response aprendimos en el artículo de Response HTTP en Laravel. Sobre las Cookies en el artículo anterior Cookies en Laravel.
Vistas de la aplicación
Para acabar nos faltaría mostrar el código de la vista que hemos creado para esta práctica. Es la parte más sencilla porque básicamente es código HTML. Es una vista de tipo Blade, por lo que tendrá extensión .blade.php (ya que hemos usado sintaxis blade para embutir datos que recibimos en la vista en el código HTML {{$variable}}). Vimos más sobre vistas en el artículo de introducción a las vistas en Laravel.

Esta vista es la del formulario:

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Personalizacion</title>
	<style>
	body{
		font-size: {{$fuente}};
	}
	</style>
</head>
<body>
	<p>fuente: {{$fuente}}</p>
	<form action="/personalizacion" method="post">
		Fuente: 
		<select name="fuente">
			<option value="24pt">Grande</option>
			<option value="16pt">Mediana</option>
			<option value="12pt">Pequeña</option>
		</select>
		<br />
		<input type="submit" value="Enviar">
	</form>
</body>
</html>
Observa que en la declaración de estilos, etiqueta STYLE en el HEAD, usamos la variable de la fuente para personalizar el tamaño de letra del BODY. Luego hay un párrafo ya en el cuerpo de la página donde usamos esa misma variable que nos mandan a la vista {{$fuente}}. El resto es un formulario HTML de toda la vida.

Con eso es todo, si has seguido con atención el manual de Laravel, estamos convencidos que podrás reproducir este ejemplo y mejorarlo por tu cuenta con adicionales opciones de personalización de estilos en la página.


//? Cómo se gestiona el trabajo con bases de datos en Laravel

En Laravel existen diversas vías para trabajar con bases de datos, a distintos niveles. Principalmente podremos trabajar con:

Nota: Cuando decimos "a distintos niveles" nos referimos a lo mismo que se refieren los lenguajes de alto y bajo nivel. Los de alto nivel son más cercanos al lenguaje de las personas y los de bajo nivel más cercanos a la máquina. En este caso son distintos niveles con respecto al sistema gestor de base de datos.
Raw Sql es el de más bajo nivel y Eloquent es el de más alto nivel.

//! Raw SQL: consultas nativas de nuestro sistema gestor de bases de datos.

//! Fluent Query Builder: un sistema de construcción de las consultas por medio de código, independiente del sistema gestor de bases de datos usado, y sin tener que escribir a mano el código SQL.

//! Eloquent ORM: un ORM avanzado, pero sencillo de usar, para trabajar con los datos como si fueran objetos y abstraernos de que realmente estén almacenados en tablas de la base de datos.

El desarrollador es el responsable de saber de qué manera le interesa trabajar para cumplir mejor sus objetivos. Generalmente, en la mayor parte de los casos, trabajar con Eloquent será más sencillo, por lo que habitualmente será la solución preferida. Sin embargo, las necesidades de nuestro proyecto, ya sea por complejidad del modelo de negocio o por buscar una mejora del rendimiento, podremos preferir opciones de más bajo nivel como Query Builder o incluso Raw SQL.

//? Bases de datos compatibles

Laravel trabaja con diversos motores de base de datos por debajo. Son los siguientes:

MySQL
Postgres
SQLite
SQL Server

La elección del sistema de base de datos corre por nuestra cuenta y aunque sean motores diferentes, a la hora de la práctica el tratamiento que haremos, a nivel de código, para trabajar con cualquiera de ellos será el mismo, si trabajamos en las capas de más alto nivel como el query builder o el ORM.

//? Configuración de la base de datos

El primer paso que tendremos que hacer para trabajar con una base de datos es configurarla en nuestro sistema. Esto se realiza desde un par de lugares.

//! Archivo .env:
Este archivo se encuentra en la carpeta raíz del proyecto y contiene información sobre el entorno. Son básicamente una serie de variables de entorno que tienen entre otras información sobre la base de datos. Este archivo de entorno podrá en un mismo proyecto tener valores distintos, en distintas máquinas, por ejemplo cuando está en un servidor en producción y uno en desarrollo.

Nota: Como te podrás imaginar, este archivo no se suele incluir en el sistema de control de versiones, porque cada máquina tendrá sus variables de entorno y no se desean compartir entre unas y otras. Por eso, cuando usas Git, lo más normal es que lo metas en el archivo "gitignore".

//! Archivo config/database.php:
Es un archivo donde se definen los valores de conexión de la base de datos. 
Muchos de ellos los toma directamente de las definiciones del archivo .env (el entorno), pero además se definen asuntos como el driver, codificación, etc.
Si tuviéramos varias conexiones con base de datos deberíamos definirlas todas en este archivo.

Nota: Si has instalado Laravel mediante Homestead la inmensa mayoría de las configuraciones ya están realizadas. En los archivos .env y config/database.php no tendrás necesidad de editar nada. Si has instalado Laravel por otros medios tendrás que configurarlos a mano. No obstante, aunque Homestead te ahorre este paso, merece la pena echarle un vistazo a ambos ficheros para ver cómo y dónde se especifican todos los valores de conexión.

Los valores de conexión con MySQL predeterminados que tenemos en Homestead son los siguientes:

Host: localhost
Usuario: homestead
Clave: secret
Base de datos: homestead
Aunque esta información la puedes ver y confirmar en el archivo .env de la carpeta raíz de tu instalación de Laravel.

//? Modelos en Laravel

Los modelos son las clases que tienen el código donde se realizará el acceso a los datos, 
por tanto, los accesos a las bases de datos son responsabilidad de los modelos. 
En Laravel se han diseñado para que funcionen con muy poco código y para ello el programador debe respetar una serie de convenciones.

Esas convenciones son las que permiten que podamos ahorrar código. Si no las respetamos simplemente nos veríamos obligados a escribir algo de código para adaptar los modelos a nuestras circunstancias. Estas reglas son importantes para comenzar:

//!El nombre del modelo tiene que ser en singular y mayúscula 
//! y la tabla a la que acceden estará en plural y minúscula.
Por ejemplo: modelo "User" para tabla "users". 
Modelo "Product" para tabla "products".

Por defecto busca siempre la llave primaria con el nombre de campo "id".

//! Eloquent usa las columnas created_at y updated_at para actualizar automáticamente esos valores de tiempo.

Nota: Obviamente, hay mucha más información que tendremos que abordar sobre los modelos, que veremos más adelante. No obstante recuerda que tuvimos una introducción a los modelos en Laravel en la que ofrecimos más información y vimos algo de código.

//? Migraciones y seeders


Connection name será lo que deseemos. El connection method será "Standard (TCP/IP)" y luego los parámetros de conexión los ya conocidos, 127.0.0.1 (o quizás 192.168.10.10 si es tu caso) para el "Hostname", homestead para "Username" y listo.

Si no ponemos la clave en la primera ventana de conexión nos aparecerá una nueva ventana más tarde para indicarla.



Como decimos, desde estos programas en principio nos limitaremos a observar cómo han ido las migraciones al ejecutarse o volverse hacia atrás, así como la intervención de los modelos para modificar los datos de la aplicación. No obstante, si lo preferimos, podríamos usar también estos programas para definir las bases de datos, en cuyo caso perderíamos las ventajas de trabajar con el sistema de migraciones.

En el siguiente artículo entraremos de lleno en la parte práctica y conoceremos el sistema de migraciones para poder comenzar a crear las nuestras.


//? Práctica de acceso a base de datos en Laravel

Realizaremos una práctica de acceso a base de datos en Laravel, con varias operaciones a partir de modelos de Eloquent en Laravel 5.

Vamos a hacer una pausa para la práctica en la sección de bases de datos del Manual de Laravel 5. Hemos visto de manera detallada la creación y modificación de tablas mediante las migraciones y además hemos conseguido insertar datos de prueba en las tablas gracias a los seeders, así que nos resta jugar un poco con todo ello.

Nuestro ejercicio nos permitirá poner en práctica los conocimientos que ya poseemos hasta el momento y explorar algún área nueva, sobre todo en lo que respecta a los modelos. El objetivo es realizar un sencillo sistema para listar datos que tenemos en una tabla y para insertar datos nuevos en dicha tabla. Resultará bastante sencillo de entender si digo que nos quedaremos a la mitad del camino de lo que se conoce como CRUD. Osea, veremos solamente el "CR" Create y Read, dejando para más adelante el "UD" Update y Delete.

//! Nota: Todo lo que vamos a conocer de nuevo en este artículo son cosas que nos ofrece el ORM de Laravel, Eloquent. 


//? Rutas

Comenzaremos observando las rutas nuevas que hemos creado para este ejemplo.

route::get('libros', 'BookController@index');
route::get('libros/{id}', 'BookController@show')->where(['id' => '[0-9]+']);
route::get('libros/crear', 'BookController@create');
route::post('libros/crear', 'BookController@store');

Por orden de aparición, las rutas serán usadas para las siguientes páginas:

libros: para ver un listado de todos los libros
libros/{id}: para ver un detalle de un libro en particular
libros/crear (GET): para mostrar el formulario con el que crearemos libros
libros/crear (POST): para recibir el formulario con los datos de un nuevo libro y si valida correctamente, insertarlo

Con la lectura del código de las rutas debería quedar claro que el controlador encargado de ejecutar todas las solicitudes se llama BookController. Los métodos que tendremos que definir, con el código de cada operación, se llaman index(), show(), create(), store().

Nota: Recuerda que tienes más información sobre las rutas en capítulos anteriores. Puedes ver el artículo Parámetros en las rutas, así como Verbos en las rutas.
Controlador BookController
Vamos a contar con un único controlador que realizará todas las acciones que serán necesarias para resolver nuestros objetivos.

El controlador, como sabes, se puede generar con un comando de artisan: “make:controller”. Es parte te la dejamos para ti, porque ya lo vimos en el capítulo de introducción a los controladores, pero sin embargo quiero que prestes atención a los nombres de las acciones en el controlador por defecto que se ha creado. Son los mismos que hemos proyectado en las rutas anteriores, así que ya tenemos mucho del trabajo adelantado.

//? El listado de todos los libros:


public function index()
{
    $libros = Book::all();
    return view('libro.todos', ['libros' => $libros->toArray()]);
}

Hacemos una búsqueda de los libros a través del modelo Book. Luego llamamos a una vista que se encargará de mostrar el listado de todos los libros.

Nota: El modelo Book lo debes haber creado al poner en práctica artículos anteriores, puesto que ya lo usamos para crear el seeder de la tabla de libros (books). De todos modos, de momento es un modelo vacío de contenido, por lo que si no lo tienes lo generas directamente con el comando “make:model” del asistente artisan.
Cuando solicitamos información a un modelo como en este caso, Book::all(), lo que nos devuelve el método all() es un objeto colección collection. 
No hemos llegado a explicar qué hay de útil detrás de las colecciones y por ahora vamos a dejarlo aparte, pero comenzaremos a usarlas. De momento lo único que hacemos con la colección es invocar el método toArray(), que nos devuelve los datos que contiene, pero en formato de array.

Ese array generado a partir de la colección se lo pasamos a la vista para que sea capaz de pintar los libros que se han encontrado en la consulta a la tabla.

//! Por cierto, en el controlador no se te debe olvidar hacer el “use” de la clase donde está el modelo Book.

use App\Book;

//? El detalle de un libro:


public function show($id)
{
    $libro = Book::find($id);
    if (!is_null($libro))
        return view('libro.mostrar', ['libro' => $libro->toArray()]);
    else
        return response('no encontrado', 404);
}


Este método recibe como parámetro el id del libro que se desea visualizar. Ese id se lo pasamos a la ruta y gracias al where() del código de la ruta ya está comprobado que sea un número.

Nota: Recuerda el código de la ruta:

route::get('libros/{id}', 'BookController@show')->where(['id' => '[0-9]+']);


//! Tal como está construida, solo se activará cuando el parámetro id sea un número.

En esta acción del controlador solicitamos información al modelo de los libros (Book) mediante el método find(), que sirve para buscar un elemento a partir de su clave primaria. Si encontró alguna cosa me devolverá la colección con aquello que se haya encontrado. Si no encuentra nada me devolverá null. 

En función del valor de retorno, se realizan cosas distintas.

Devuelvo una vista para mostrar el libro, en caso que no sea nulo, enviándole a la vista los datos del libro en un array
O bien,

si no se encontró nada, devuelvo un objeto response con un error 404 de página no encontrada y un mensaje

Formulario de creación de un libro:

Ahora veamos la acción que comienza el proceso de creación de un libro, que se encarga de mostrar el formulario para introducir los datos de un libro.

public function create()
{
    return view('libro.formlibro');
}

Este es el más sencillo de todos los métodos del controller, porque solo tiene la llamada a una vista, que tendrá el HTML del formulario de alta de un libro.

Creación del libro, a partir de los datos del formulario:

    Para acabar el controlador nos queda ver el método store, que es el que realmente se encarga de crear el nuevo libro.

public function store(Request $request)
{
    $this->validate($request, [
        'name' => 'required|min:5',
        'author' => 'required|min:8',
        'isbn' => 'required'
    ]);

    Book::create($request->all());
    return redirect('/libros');
}
El método store() recibe la Request, dependencia que se inyecta en la llamada al método de la acción, necesario porque vamos a tener que recuperar los datos de la solicitud POST (los datos enviados en el formulario).

Dentro del método realizamos una validación de los datos recibidos por formulario y si todo ha ido bien, es cuando creamos el recurso, gracias al método create() del modelo Book, enviándole los datos de la solicitud, todos convertidos a array mediante el método all().

Una vez creado el libro en la tabla, se redirige a la página “home” de libros, que se encarga de mostrar el listado de los libros que hay en el sistema.

Esta es la parte más compleja de la práctica, porque entran en juego las validaciones, sin embargo esperamos que puedas recordar los mecanismos que ya fueron relatados en el artículo de validaciones en Laravel.

//? Modificación en el modelo Book

Tal como está nuestro código y el uso que hacemos del modelo con el método Book::create(), vamos avisando que Laravel nos arrojará una excepción cuando se intente crear el nuevo libro.

MassAssignmentException in Model.php line 424:

No es la primera vez que nos aparece un error similar, así que vamos a intentar concretar ya de una manera sencilla qué es lo que está pasando.

El método Book::create() nos permite crear un elemento e insertarlo en la base de datos. Para ello recibe un array y usa todos los datos que le estamos pasando. Esos datos en nuestro ejemplo vienen directamente de la solicitud y los volcamos tal cual para poder crear ese nuevo libro:

Book::create($request->all());

Imagina que viene a través de la solicitud un dato que realmente no estábamos esperando, por ejemplo un dato de control que usas internamente para saber el estado de un libro y no deseas que sea parte de la entrada del usuario. ¿Cómo podría ocurrir que te manden otros datos, además de los que deseas? pues simplemente un usuario con conocimientos mínimos podría crear otros campos en el formulario, editando la página con las herramientas de desarrolladores y así se enviarían también en la solicitud por el método POST al enviar ese formulario normalmente.

Así que Laravel, para evitar estas cosas, al usar el método create() que es potencialmente peligroso por una posible inyección de datos no deseados, obliga a que tengas declarados en el modelo aquellos campos que se van a poder insertar al hacer un create().

Nota: Hay otros métodos de Eloquent para crear elementos en las tablas, que no tendrían este problema porque de manera explícita se indican campo a campo las columnas a insertar con los valores.
Esta configuración se realiza a través de una propiedad que tendremos que crear dentro del modelo, llamada $fillable. El código te quedará así:

class Book extends Model
{
    protected $fillable = ['name', 'author', 'isbn'];
}

//? Vistas de la aplicación

Ya solo nos queda ver las vistas que hemos creado para esta práctica. Todas las hemos situado en la carpeta resources/views/libro.

Nota: Todavía nos quedan cosas por ver de las vistas, sobre todo del motor de plantillas Blade, pero recuerda que ya pudimos encontrar una introducción a las vistas.
Vista libro.todos: (archivo todos.php)
Esta vista hace el recorrido a array de libros que se le ha pasado y los va mostrando uno a uno.

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Todos los libros</title>
</head>
<body>
	<?php foreach ($libros as $libro): ?>
		<p>
			<?=$libro['name']?>, por <?=$libro['author']?>
			<br>
			ISBN: <?=$libro['isbn']?>
		</p>
	<?php endforeach ?>
</body>
</html>

En este caso usamos un “foreach” clásico de PHP (con el código sugerido como alternativa a las vistas), aunque más tarde al aprender Blade veremos una forma más clara de escribir este mismo código.

Vista libro.mostrar: (archivo mostrar.php) Esta segunda vista muestra el detalle de un libro.

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Mostrar un libro</title>
</head>
<body>
	<h1>
		<?=$libro['name']?>
	</h1>
	<p>
		Por <?=$libro['author']?>
		<br>
		ISBN: <?=$libro['isbn']?>
	</p>
</body>
</html>

Nota: Es cierto que había otros campos en la tabla de libro, pero no los hemos querido usar por ahora, para no incrementar la dificultad de esta práctica.
vista libro.formlibro: archivo formlibro.blade.php Esta tercera vista, y última, es la que usaremos para mostrar el formulario de un libro, que de momento estamos usando para la creación de libros. Hemos elegido como extensión .blade.php porque vamos a usar un pedazo de código con sintaxis blade para mostrar los posibles errores de validación de los datos del formulario.

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Crear un libro</title>
</head>
<body>
	<h1>Crear un libro</h1>
	@if(count($errors) > 0)
		<div class="errors">
			<ul>
			@foreach($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
			</ul>
		</div>
	@endif
	<form action="/libros/crear" method="post">
		Nombre: <input type="text" name="name" value="{{old('name')}}">
		<br>
		Autor: <input type="text" name="author" value="{{old('author')}}">
		<br>
		ISBN: <input type="text" name="isbn" value="{{old('isbn')}}">
		<br>
		<input type="submit" value="Crear">
	</form>
</body>
</html>

Esta vista tiene varios detalles importantes, primero el recorrido a los errores de validación y luego la “memoria” en los campos de formulario para que sean capaces de recordar su estado cuando fue enviado el formulario la última vez. Todo esto no lo vamos a explicar porque se vio con suficiente detalle en el capítulo de validaciones en Laravel.

Con esto termina esta práctica, ya solo faltaría poner en marcha el ejercicio accediendo a las rutas de la aplicación definidas.



//!* Emigrar aplicacion de Laravel en instancia AWS ubuntu ingnx 
Para migrar una aplicación de Laravel a una instancia de AWS con Ubuntu y nginx, puedes seguir los siguientes pasos:

Crea una instancia de AWS: en primer lugar, debes crear una instancia de AWS con Ubuntu como sistema operativo. Puedes usar la consola de AWS o la línea de comandos para crear la instancia.

Instala PHP y nginx: una vez que tienes la instancia creada, debes instalar PHP y nginx en ella. Puedes usar los siguientes comandos para instalar PHP y nginx:

Copy code
sudo apt-get update
sudo apt-get install php7.4-fpm nginx
Configura nginx: una vez que tienes PHP y nginx instalados, debes configurar nginx para que pueda servir la aplicación de Laravel. Para ello, debes
 crear un archivo de configuración para la aplicación en la carpeta /etc/nginx/sites-available/ y luego habilitarlo con el siguiente comando:
Copy code
sudo ln -s /etc/nginx/sites-available/mi_aplicacion /etc/nginx/sites-enabled/
Aquí tienes un ejemplo de archivo de configuración para una aplicación de Laravel:

Copy code
server {
    listen 80;
    listen [::]:80;

    root /var/www/mi_aplicacion/public;
    index index.php index.html index.htm;

    server_name mi_aplicacion.com;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php7.4-fpm.sock;
    }

    location ~ /\.ht {
        deny all;
    }
}
Sube la aplicación: una vez que tienes nginx configurado, debes subir la aplicación a la instancia de AWS. Puedes usar un cliente FTP o una herramienta 
de transferencia de archivos como scp para subir la aplicación a la carpeta /var/www/mi_aplicacion/.

Configura la base de datos: finalmente, debes configurar la conexión a la base de datos de la aplicación. Para ello, debes editar el archivo .env de 
la aplicación y establecer los valores de DB_HOST, DB_DATABASE, DB_USERNAME y DB_PASSWORD con los datos de tu base de datos.



<script src="{{ asset('tmpl/swal/sweetalert2.all.min.js') }}"></script>
<script type="text/javascript">
    //20220719  C24 20230110
    document.getElementById('btnPagarLinea').addEventListener('click', (e) => {
        e.preventDefault();
        console.log("este es ");
        console.log(e);

        FormPagoLinea.setAttribute('action', 'https://www.adquiramexico.com.mx/clb/endpoint/gGuadalupe');

       if (document.getElementById('ic_referencia').value == '05144045' || document.getElementById('ic_referencia').value == '04064002' || document.getElementById('ic_referencia').value == '20108019') {
              FormPagoLinea.setAttribute('action', 'https://prepro.adquiracloud.mx/clb/endpoint/gGuadalupe');             
            // document.getElementById('it_servicio').value = "02";
           // confirm("es 05144045");
           // console.log("es 01001012 y no trece");
         }

        document.getElementById('FormPagoLinea').submit();
    });
</script>



?>