<?php  
20231124
// PARADIGMA OOP

// La herencia y el polimorfismo en los objetos se denominan paradigma de programación orientado a objetos




//! - La herencia 
// es un mecanismo que permite que una clase herede las propiedades y métodos de otra clase

// - El polimorfismo 
// es un mecanismo que permite que diferentes objetos respondan de manera diferente a una misma llamada de método.

//!enumera los mecanismos en OOP  

// Los mecanismos en la programación orientada a objetos (OOP) son los conceptos y técnicas que se utilizan para crear programas
// orientados a objetos. Los mecanismos más comunes en OOP son

// - Objetos:
//  Un objeto es un modelo de una entidad del mundo real. Un objeto tiene estado (datos) y comportamiento (métodos).
// - Clases: 
// Una clase es un modelo de un tipo de objeto. Una clase define el estado y el comportamiento de los objetos que se crean a partir de ella.
// - Herencia:
//  La herencia es el mecanismo que permite que una clase herede las propiedades y métodos de otra clase. Esto permite crear clases más complejas a partir de clases más simples.
// - Polimorfismo:
// El polimorfismo es el mecanismo que permite que diferentes objetos respondan de manera diferente a una misma llamada de método.
// Esto permite crear código que sea más general y más flexible.
// - Encapsulamiento:
// El encapsulamiento es el mecanismo que oculta los detalles internos de un objeto de los usuarios del objeto. 
// Esto permite proteger la información sensible y facilitar el mantenimiento del código.
// - Abstracción:
// La abstracción es el mecanismo que permite crear modelos de objetos del mundo real que son más fáciles de entender
// y utilizar que los objetos reales.
// - Mensajes:
//  Un mensaje es una solicitud enviada a un objeto para que realice una acción.


// - El encapsulamiento 
// se logra utilizando modificadores de acceso, como:
    //  private, protected y public.
// -  La abstracción 
// se logra utilizando
//   interfaces
// que son una forma de definir un contrato que deben cumplir las clases.

//!  interfaz de la clase

#region
<?php 
//!   el conjunto de métodos públicos que se pueden llamar desde fuera de la clase. 

// !- El encapsulamiento se puede lograr utilizando
// ! modificadores de acceso, como private y protected. 

// ejemplo de interface
interface Estudiante {

    public function getId();

    public function setId($id);

    public function getNombre();

    public function setNombre($nombre);
}


- Define la clase pasante usando la interface Estudiante

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Interfaces\Estudiante;

class Pasante extends Model implements Estudiante
{
    protected $table = 'pasantes';

    protected $fillable = [
        'id_pasante',
        'id_estudiante',
        'empresa',
        'area',
        'fecha_inicio',
        'fecha_fin',
    ];

    public function getNombre()
    {
        return $this->estudiante->nombre;
    }

   


    public function getEmpresa()
    {
        return $this->empresa;
    }

    public function getArea()
    {
        return $this->area;
    }

    public function getFechaInicio()
    {
        return $this->fecha_inicio;
    }

    public function getFechaFin()
    {
        return $this->fecha_fin;
    }

    public function setNombre($nombre)
    {
        $this->estudiante->nombre = $nombre;
    }

    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;
    }

    public function setArea($area)
    {
        $this->area = $area;
    }

    public function setFechaInicio($fechaInicio)
    {
        $this->fecha_inicio = $fechaInicio;
    }

    public function setFechaFin($fechaFin)
    {
        $this->fecha_fin = $fechaFin;
    }

    public function getEstudiante()
    {
        return $this->belongsTo('App\Estudiante', 'id_estudiante');
    }
}
?>
#endregion
//! - El polimorfismo 
#region
<?php 
//  permite que diferentes objetos respondan de manera diferente a la misma llamada. 
//  Esto se logra mediante la herencia y la sobrecarga de métodos.
// El polimorfismo se da cuando una clase padre hereda un método y se puede modificar en las clases hijas. 
//  Esto se llama sobrescritura de métodos.


//  Un ejemplo de polimorfismo es el siguiente:

class Animal {

    public function makeSound() {
        echo "¡Soy un animal!";
    }

}

class Dog extends Animal {

    public function makeSound() {
        echo "¡Guau!";
    }

}

class Cat extends Animal {

    public function makeSound() {
        echo "¡Miau!";
    }

}

$animal = new Animal();
$animal->makeSound(); // ¡Soy un animal!

$dog = new Dog();
$dog->makeSound(); // ¡Guau!

$cat = new Cat();
$cat->makeSound(); // ¡Miau!

ejemplo de poliformismo virtual 

namespace App;

use App\Estudiante;
use App\Pasante;

class Polimorfismo
{
    public function mostrarInfo($estudiante)
    {
        if ($estudiante instanceof Estudiante) {
            echo "Nombre: " . $estudiante->nombre . ", Apellido: " . $estudiante->apellido . ", Carrera: " . $estudiante->carrera;
        } else if ($estudiante instanceof Pasante) {
            echo "Nombre: " . $estudiante->nombre . ", Apellido: " . $estudiante->apellido . ", Empresa: " . $estudiante->empresa . ", Área: " . $estudiante->area;
        }
    }
}

// Controller
$estudiante = new Estudiante();
$estudiante->nombre = "Juan Pérez";
$estudiante->apellido = "García";
$estudiante->carrera = "Ingeniería de Software";

$pasante = new Pasante();
$pasante->nombre = "María López";
$pasante->apellido = "Rodríguez";
$pasante->empresa = "Google";
$pasante->area = "Desarrollo de Software";

$polimorfismo = new Polimorfismo();
$polimorfismo->mostrarInfo($estudiante);
$polimorfismo->mostrarInfo($pasante);
?>
#endregion

- funciones lambda =>

$mayor = fn($a, $b) => $a > $b ? $a : $b;

echo $mayor(5, 10); // 10

?>
