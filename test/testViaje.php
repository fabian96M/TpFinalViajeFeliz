<?php
include_once "../Datos/BaseDatos.php";
include_once "../Datos/Viaje.php";
include_once "../Datos/Responsable.php";
include_once "../Datos/Pasajero.php";
/* Inicializacion de var */
$bandera = true;
/* se crea e instancia los datos de 1 reponsable */
$responsable1 = new Responsable();
$responsable1->carga("Pedro", "Coral", 6334, 123456789);
/* se crea e instancia 1 empresa*/
$empresa1 = new Empresa();
$empresa1->carga(007, "via bariloche", "calle falsa 123");
/* Se crea e instancia los datos de 3 pasajeros */

    $pasajero1 = new Pasajero();
    $pasajero2 = new Pasajero();
    $pasajero3 = new Pasajero();

    $pasajero1->carga("Gerardo", "Martinez", 23840952, 2040234052, 1235);
    $pasajero2->carga("Andrea", "Quinta", 23948025, 2049029343, 1235);
    $pasajero3->carga("Eduardo", "Giron", 23940294, 2293402938, 1235);
/* Instanciamos un responsable */


/* Instanciamos 1 viaje */
$viaje1 = new Viaje();
/* cargamos los datos de un viaje a la clase */
$viaje1->carga(1235, "Mar del plata", 5,007, 6334, 25000);
$viajes[] = $viaje1;

$responsable1->insertar();
$empresa1->Insertar();
$pasajero1->insertar();
$pasajero2->insertar();
$pasajero3->insertar();
$viaje1->insertar();
/* Presentamos las opciones al usuario (carga de datos de viaje, modificar datos de viaje o mostrar los datos del viaje) */
while ($bandera) {
    echo "\n A continuacion ingrese la opcion deseada: \n ";
    echo "\n
1 - Cargar Datos de un viaje nuevo \n
2 - Modificar Datos de un Viaje \n
3 - Mostrar Datos de un viaje \n
4 - Salir \n";

    /* Recibimos la opcion y segun ella ejecutamos la secuencia a realizar */
    $eleccion1 = trim(fgets(STDIN));

    /* Segun la eleccion del usuario se ejecutara una secuencia */
    switch ($eleccion1) {
        case 1:
            $viajes[] = cargaDatosViaje();
            echo "Se ha añadido un nuevo viaje";
            break;
        case 2:
            echo "\n Ingrese el numero del viaje a modificar \n";
            $numViaje = trim(fgets(STDIN));
            menuDerivadora();
            $eleccion = trim(fgets(STDIN));
            modificarViaje($eleccion, $viajes[$numViaje - 1]);

            break;
        case 3:
            echo "\n Ingrese el numero de viaje que desee visualizar \n";
            $numViaje = trim(fgets(STDIN));
            echo "\n " . $viajes[$numViaje - 1] . "\n";
            break;
        case 4:
            $bandera = false;
            echo "\n Tenga buen dia \n";
            break;
        default:
            echo "La opcion ingresada no es valida";
            break;
    }
}


/* FUNCIONES PARA CARGA DE DATOS */

/* funcion para crear un pasajero */
/* crea un objeto con los datos de un pasajero */
function crearPasajero()
{
    /* Solicitamos los datos del pasajero */
    echo "\n Ingrese datos del pasajero \n";
    echo "\n Ingrese Nombre:  \n";
    $nombre = trim(fgets(STDIN));
    echo "\n Ingrese Apellido: \n";
    $apellido = trim(fgets(STDIN));
    echo "\n Numero de Documento: \n";
    $numDoc = trim(fgets(STDIN));
    echo "\n Numero de telefono\n";
    $numTelefono = trim(fgets(STDIN));
    /* Creamos un los objetos con los datos del pasajero*/
    $objPasajero = new Pasajero($nombre, $apellido, $numDoc, $numTelefono);
    /* Retornamos el objeto con los datos */
    return $objPasajero;
}
/* Funcion para crear un responsable */
function cargarResponsable()
{

    /* Solicitamos los datos del Responsable */
    echo "\n Ingrese datos del Responsable \n";
    echo "\n Ingrese Nombre:  \n";
    $nombre = trim(fgets(STDIN));
    echo "\n Ingrese Apellido: \n";
    $apellido = trim(fgets(STDIN));
    echo "\n Numero de Empleado: \n";
    $numEmp = trim(fgets(STDIN));
    echo "\n Numero de Licencia\n";
    $numLic = trim(fgets(STDIN));
    /* Creamos un los objetos con los datos del pasajero*/
    $objResponsable = new Responsable($nombre, $apellido, $numEmp, $numLic);
    /* Retornamos el objeto con los datos */
    return $objResponsable;
}
/* Funcion para cargar los pasajeros de un viaje  */
function cargarPasajeros($objViaje)
{
    $continuar = true;
    $i = 0;
    /* Realizamos un echo explicando las reglas de carga */
    echo "\n A continuacion ingrese los datos de cada pasajero, si hay espacio para mas se le consultara si desea añadir otro mas\n";
    /* Solicitamos los datos dentro del bucle consultando al final de cada iteracion */
    do {
        $objPasajero = crearPasajero();
        /* Verifiamos que no se encuentre cargado un pasajero igual */
        if ($objViaje->pasajeroCargado($objPasajero)) {
            echo "\n El pasajero ya es encuentra cargado \n";
        } else {
            /* cargamos el pasajero a la coleccion del objeto viaje */
            $objViaje->cargarPasajero($objPasajero);
            $i++;
            if ($i == $objViaje->getMaxPasajeros()) {
                /* se avisara cuando ya finalice el ciclo por falta de espacio en el array de pasajeros */
                echo "\n Ya no es posible añadir mas pasajeros (Maximo alcanzado)\n";
            } else {
                echo "\n Desea añadir otro pasajero? Por favor pulse: \n
             |1) Para si  \n
             |2) Para No \n ";
                $eleccion = trim(fgets(STDIN));
                /* verificamos si el usuario quiere finalizar el ciclo  */
                if ($eleccion == 2) {
                    $continuar = false;
                }
            }
        }
    } while ($i < $objViaje->getMaxPasajeros() && $continuar);
}

/* Funcion para cargar los datos de un viaje */
function cargaDatosViaje()
{
    $arrayPasajeros = [];
    /* solicitamos los datos del viaje y almacenamos cada uno */
    echo "\n Ingrese un codigo de viaje \n";
    $CodViaje = trim(fgets(STDIN));
    echo "\n Ingrese un Destino \n";
    $destino = trim(fgets(STDIN));
    echo "\n Ingrese un Maximo de pasajeros \n";
    $maxPasajeros = trim(fgets(STDIN));
    $responsableV = cargarResponsable();
    $arrayPasajeros = [];
    $viaje = new Viaje($CodViaje, $destino, $maxPasajeros, $arrayPasajeros, $responsableV);
    cargarPasajeros($viaje);
    return $viaje;
}
/* FUNCIONES PARA MODIFICAR DATOS */
///////////////////////////////////////
function menuDerivadora()
{
    /* Muestra el menu de opciones de las funciones para modificar datos */
    echo "\n A continuacion ingrese el numero de acuerdo a lo que desee modificar \n";
    echo "|1 ) Para modificar el codigo de viaje: \n";
    echo "|2 ) Para modificar el Destino de viaje: \n";
    echo "|3 ) Para modificar el Maximo de pasajeros de un viaje: \n";
    echo "|4 ) Para modificar los parajeros de un viaje: \n";
    echo "|5 ) Para modificar un pasajero de un viaje: \n";
}
function modificarViaje($numEleccion, $viaje)
{
    /* Recibe un numero por parametro y deriva segun el numero  */

    switch ($numEleccion) {
        case 1:
            echo "\n Indique el Nuevo codigo de viaje \n";
            $viaje->setCodViaje(trim(fgets(STDIN)));
            break;
        case 2:
            echo "\n Ingrese un nuevo destino de viaje \n";
            $destino = trim(fgets(STDIN));
            $viaje->setDestino($destino);
            break;
        case 3:
            echo "\n Ingrese un nuevo maximo de pasajeros no menor a ".$viaje->cantPasajeros()."\n";
            $nuevoMaxPasajeros = trim(fgets(STDIN));
            if($viaje->modificarMaximoPasajeros($nuevoMaxPasajeros)){
                echo "\n Se establecio el nuevo maximo a ".$nuevoMaxPasajeros." pasajeros\n";

            }
            else{
                echo "\n ERROR: No se pudo establecer como maximo debido a la cantidad de pasajeros ya cargada \n";
            }
            break;

        case 4:
            /* vaciamos la coleccion de pasajeros */
            $viaje->setColPasajeros([]);
            /* cargamos nuevos usando la funcion de carga */
            cargarPasajeros($viaje);
            break;

        case 5:
            modificarPasajero($viaje);
            break;
        case 6:
            $viaje->setResponsable(cargarResponsable());

        default:
            echo "\n El numero ingresado no esta contemplado \n";
            break;
    }
}
/* colDatosPasajero: solicita y crea una coleccion con los datos a modificar de un pasajero */
function colDatosPasajero() {
    $datosPasajero = [];  // Inicializa el arreglo

    echo "\nA continuación, ingrese los datos que desee modificar. Deje en blanco y presione Enter para no modificar un dato.\n";
    echo "\nNombre: ";
    $nombre = trim(fgets(STDIN));
    $datosPasajero[0] = !empty($nombre) ? $nombre : null;

    echo "\nApellido: ";
    $apellido = trim(fgets(STDIN));
    $datosPasajero[1] = !empty($apellido) ? $apellido : null;

    echo "\nNúmero de documento: ";
    $numDoc = trim(fgets(STDIN));
    $datosPasajero[2] = !empty($numDoc) ? $numDoc : null;

    echo "\nTeléfono: ";
    $telefono = trim(fgets(STDIN));
    $datosPasajero[3] = !empty($telefono) ? $telefono : null;

    return $datosPasajero;
}
/* modifica un pasajero de un arreglo de pasajero de un objeto viaje  */
function modificarPasajero($objViaje)
{
    
    /* creamos un arreglo para almacenar los datos de un pasajero */
    $colDatosPasajero = [];
    $bandera = true;
    /* solicitamos el numero de pasajero */
    echo "\n Ingrese el numero de pasajero a modificar \n";

    /* confirmamos que sea una posicion valida */
    while ($bandera) {
        $numPasajero = trim(fgets(STDIN));
        if ($numPasajero <= $objViaje->cantPasajeros() && $numPasajero > 0) {
            /* Si el numero esta dentro del rango */
            $bandera = false;
        }else {
            echo "\n El numero no es valido, porfavor ingrese otro \n";
        }
    }
    /* si es valida creamos un pasajero usando la funcion crearPasajero */
    $colDatosPasajero = colDatosPasajero();
    /* Verificamos que el pasajero no este cargado en la coleccion de pasajeros */
    $resultado = $objViaje->modificarPasajero($numPasajero, $colDatosPasajero);
    if ($resultado) {
        echo "\n ERROR: El pasajero que intenta cargar ya ha sido cargado anteriormente al registro \n";
    }
    else{
        echo "\n El arreglo se cargo exitosamente";
    }
}
