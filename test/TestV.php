<?php
include_once "../Datos/BaseDatos.php";
include_once "../Datos/Empresa.php";
include_once "../Datos/Pasajero.php";
include_once "../Datos/Responsable.php";
include_once "../Datos/Viaje.php";

$bandera = true;
/* Se muestra un menu de opciones al usuario */
/* Presentamos las opciones al usuario (carga de datos de viaje, modificar datos de viaje o mostrar los datos del viaje) */
while ($bandera) {
    echo "\n A continuacion ingrese la opcion deseada: \n ";
    echo "\n
1 - Para Ingresar una empresa \n
2 - Para Modificar una empresa \n
3 - Para Eliminar una empresa \n
4 - Para Ingresar Un Viaje \n
5 - Para Modificar un Viaje \n
6 - Para Eliminar un viaje \n
7 - Para Añadir pasajeros a un viaje \n
8 - Para Eliminar Pasajeros de un viaje\n
9 - Salir \n";

    /* Recibimos la opcion y segun ella ejecutamos la secuencia a realizar */
    $eleccion1 = trim(fgets(STDIN));

    /* Segun la eleccion del usuario se ejecutara una secuencia */
    switch ($eleccion1) {
        case 1:
            /* Funcion para crear una empresa */
            $objEmpresa = crearEmpresa();
            echo "\n Los siguentes datos de Empresa fueron añadidos a la base de datos: \n" . $objEmpresa;

            break;
        case 2:
            /* Funcion para modificar una empresa */
            modificarEmpresa();
            break;
        case 3:
            /* Funcion para ELIMINAR una empresa */
            $confirm = eliminarEmpresa();
            if ($confirm) {
                echo "\n La empresa se elimino de la base de datos \n";
            } else {
                echo "\n La empresa no pudo ser elminada \n";
            }
            break;
        case 4:
            /* Funcion para Ingresar un viaje */
            ingresarViaje();
            break;
        case 5:
            /* Funcion para MODIFICAR un viaje */
            modificarViaje();
            break;
        case 6:
            /* Funcion para Eliminar un viaje */
            $objViaje = new Viaje;
            if (count($objViaje->listar()) > 0) {
                echo " " . listarArreglo($objViaje->listar());
                /* consultamos cual viaje se quiere eliminar */
                echo "\n Ingrese el id de viaje del viaje que desee Eliminar \n";
                $idViaje = trim(fgets(STDIN));
                /* se confirma la eliminacion */
                $elim = eliminarViaje($idViaje);
                if ($elim) {
                    echo "\nEl viaje y sus registros asociados se eliminaron correctamente\n";
                }
            }

            break;
        case 7:
            /* Opcion añadir pasajeros a un viaje */
            $idViaje = 0;
            $objViaje = new Viaje;
            /* mostramos el listado  */
            echo "" . listarArreglo($objViaje->listar());
            echo "\n Escriba el codigo del viaje al que desee añadir pasajeros: \n";
            /* se busca el viaje segun si id */
            $idViaje = trim(fgets(STDIN));
            $encontrado = $objViaje->Buscar($idViaje);
            if ($encontrado) {
                aniadirPasajeros($objViaje);
            }

            break;
        case 8:/* Eliminar pasajero */
            $idViaje = 0;
            $objViaje = new Viaje;
            $pasajero = new Pasajero;
            /* mostramos el listado de viajes */
            echo "" . listarArreglo($objViaje->listar());
            echo "\n Escriba el codigo del viaje al que desee eliminar pasajeros: \n";
            /* se busca el viaje segun si id */
            $idViaje = trim(fgets(STDIN));
            $encontrado = $objViaje->Buscar($idViaje);
            if ($encontrado) {
                $arrPasajeros = $pasajero->listar("idviaje =".$objViaje->getIdViaje());
                if(count($arrPasajeros)>0){
                    eliminarPasajeros($arrPasajeros);
                }
                
            }

            break;
        case 9:
            /* opcion para SALIR */
            $bandera = false;
            echo "\n Tenga buen dia \n";
            break;

        default:
            echo "La opcion ingresada no es valida";
            break;
    }
}
/* Funciones generales */
function listarArreglo($arregloDeObjetos) {
    $listado = "";
    foreach ($arregloDeObjetos as $obj) {
        $listado .= " " . $obj;
    }
    return $listado;
}

/* Funciones de Empresa */

/* Crear Emresa: solicita los datos de una empresa, los carga en un objeto y los inserta en la base de datos para retornar el objeto completo o una variable null en caso de algun fallo  */
function crearEmpresa() {
    /* inicializamos una variable null */
    $objEmp = null;
    /* inicializamos un atributo como empresa */
    $objEmpresa = new Empresa();
    while ($objEmp == null) {
        /* solicitamos los datos al usuario */
        echo "\n Nombre de la Empresa: ";
        $nombreEmpresa = trim(fgets(STDIN));
        echo "\n Direccion de la Empresa: ";
        $direccionEmpresa = trim(fgets(STDIN));
        /* asignamos los atributos de la empresa  */
        $objEmpresa->carga($nombreEmpresa, $direccionEmpresa);
        /* insertamos los datos cargados a la bd */
        $confirm = $objEmpresa->Insertar();
        /* si hay confirmacion se asignara el objEmpresa al objEmp vacio */
        if ($confirm) {
            $objEmp = $objEmpresa;
        }
    }
    return $objEmp;
}

/* Funcion modificar Empresa */
function modificarEmpresa() {
    $arrEmpresas = null;
    $objEmpresa = new Empresa;
    /* se verifica que haya empresas disponibles para modificar */
    $arrEmpresas = $objEmpresa->listar();
    if (count($arrEmpresas) != 0 && $arrEmpresas != null) {
        /* se muestran las opciones de empresa */
        echo "\n Las siguientes empresas estan disponibles para ser modificadas \n";
        echo listarArreglo($arrEmpresas);
        echo "\n Ingrese la El id de la opcion que elija \n";
        $idEmpresa = trim(fgets(STDIN));
        /* se busca y guarda la empresa a modificar */
        $objEmpresa->Buscar($idEmpresa);
        /* Se presenta el menu de opciones a modificar*/
        echo "" . menuModificarEmpresa();
        $tipoModificacion = trim(fgets(STDIN));
        /* se modifica segun la opcion elegida*/
        switch ($tipoModificacion) {
            case 1:/* modificar nombre */
                echo "\n Ingrese Nombre: ";
                $objEmpresa->setNombre(trim(fgets(STDIN)));
                break;
            case 2:/* modificar direccion */
                echo "\n Ingrese Direccion: ";
                $objEmpresa->setDireccion(trim(fgets(STDIN)));
                break;
            case 3:/* modificar nombre y direccion */
                echo "\n Ingrese Nombre: ";
                $objEmpresa->setNombre(trim(fgets(STDIN)));
                echo "\n Ingrese Direccion: ";
                $objEmpresa->setDireccion(trim(fgets(STDIN)));
                break;
            default:
                echo "\n no se ha insertado una opcion valida \n";
                break;
        }
        /* se inserta la modificacion en la base de datos */
        $conf = $objEmpresa->modificar();
        /* se confirma la modificacion */
        if ($conf) {
            echo "\n La modificacion se inserto con exito en la base de datos \n";
        } else {
            echo "\n No pudo insertarse la modificacion en la base de datos \n";
        }
    }
}
/* menuModificarEmpresa */
function menuModificarEmpresa() {
    $menu = "";
    $menu .= "\n A continuacion elija una de las siguentes opciones de modificacion \n
    1) Modificar el Nombre de la empresa \n
    2) Modificar la direccion de la empresa \n
    3) Modificar Ambos atributos (Nombre y direccion) \n
    ";
    return $menu;
}
/* EliminarEmpresa */
function eliminarEmpresa() {
    $confirm = false;
    $idEmpresa =  0;
    $objViaje = new Viaje;
    $objEmpresa = new Empresa;
  
    // Se obtiene el array con las empresas que hay registradas en la bd
    $arrEmpresas = $objEmpresa->listar(); //Correcto
    /* si hay elementos en el array (si hay registros de empresas) */
  
    if (count($arrEmpresas) != 0 && $arrEmpresas != null) {
      /* se muestran las opciones de empresas registradas */
      echo "\n Las siguientes empresas están disponibles para ser eliminadas \n";
      echo listarArreglo($arrEmpresas);
      echo "\n Ingrese el ID de la empresa que desea eliminar: \n";
      /* se guarda el id de la empresa a eliminar */
      $idEmpresa = trim(fgets(STDIN));
      $busquedaEmpresa = $objEmpresa->Buscar($idEmpresa);
      // si se encuentra la empresa
      if ($busquedaEmpresa) {
        // Verifica si hay viajes asociados
        $arrViajesAsociados = $objViaje->listar("idempresa = " . $idEmpresa." ");
        if ($arrViajesAsociados != null || count($arrViajesAsociados) > 0) {
          /* En caso de que haya viajes asociados se informara que no es posible */
          echo "\n La eliminación no es posible debido a que existen viajes y datos asociados a la empresa \n";
        } else {
          // No hay viajes asociados, proceder con la eliminación directamente
          $confirm = $objEmpresa->eliminar();
        }
      } else{
        echo "\n No se ha hallado registro de la empresa señalada";
    }
    }
  
  
    return $confirm;
  }

function eliminarViajesAsociados($objEmp) {
}

//FUNCIONES DE VIAJE
/* Funcion para ingresar un viaje */
function ingresarViaje() {
    $objEmpresa = new Empresa;
    $objResponsable = new Responsable;
    $objViaje = new Viaje();
    $maxPasajeros = 0;
    $importe = 0.0;
    /* se requeriran los datos de una empresa: se elegira entre crear nueva o elegir el idempresa de una existente */
    $objEmpresa = obtenerOCrearEmpresa();
    /* se requeriran los datos de un responsable: se podra elegir uno existente o crear uno nuevo */
    echo "\n Ingrese los datos del responsable: \n";
    $objResponsable = crearResponsable();
    /* obtenemos los datos de los objetos */
    $idEmpresa = $objEmpresa->getId();
    $numEmpleado = $objResponsable->getNumEmpleado();
    /* se solicitaran los datos mas comunes del viaje */
    echo "\n Ingrese el destino: ";
    $destinoViaje = trim(fgets(STDIN));
    echo "\n Ingrese el maximo de pasajeros permitidos: ";
    $maxPasajeros = trim(fgets(STDIN));
    echo "\n Ingrese el importe de viaje: ";
    $importe = trim(fgets(STDIN));
    /* se cargaran los datos al objeto */
    $objViaje->carga($destinoViaje, $maxPasajeros, $idEmpresa, $numEmpleado, $importe);
    /* se ingresara el objeto a la bd */
    $confirmacion = $objViaje->insertar();
    /* se retornara una confirmacion */
    aniadirPasajeros($objViaje);
    return $confirmacion;
}
/* verifica si hay empresas en la base de datos para asignar a un viaje o permite crear una nueva al usuario */
function obtenerOCrearEmpresa() {
    $objEmpresa = new Empresa;
    /* verificamos si hay registros de empresas en la bd */
    echo "\n ¿ Desea revisar si hay empresas disponibles o crear una nueva? \n
    1) Revisar disponibles \n
    2) Crear Nueva Empresa \n";
    $eleccion = trim(fgets(STDIN));
    if ($eleccion == 1) {
        if (count($objEmpresa->listar()) > 0) {
            $objConfirmado = false;
            /* si hay empresas guardadas en la bd */
            echo "\n Hay " . count($objEmpresa->listar()) . " Empresas disponibles";
            $listado = listarArreglo($objEmpresa->listar());
            echo $listado;
            while (!$objConfirmado) {
                /* solicitamos el idEmpresa que se desee usar */
                echo "\n Ingrese el id de la empresa que desee poner a cargo del viaje \n";
                $idEmpresa = trim(fgets(STDIN));
                $objConfirmado = $objEmpresa->Buscar($idEmpresa);
                if (!$objConfirmado) {
                    echo "\n id Erroneo reintentelo de nuevo \n";
                }
            }
        } else {
            /* crea una nueva empresa en caso de no encontrar ninguna en la bd */
            echo "\n No hay empresas creadas en la base de datos, por favor ingrese una nueva: \n";
            $objEmpresa = crearEmpresa();
        }
    } else {
        /* crear una nueva empresa */
        echo "\n Ingrese los datos de la empresa: \n";
        $objEmpresa = crearEmpresa();
    }
    return $objEmpresa;
}
/* verifica si hay registros de responsables de viaje en la bd o permite crear uno nuevo */
function modificarViaje() {
    $bandera = true;

    $objViaje = new Viaje;
    /* si hay viajes disponibles los listamos */
    if (count($objViaje->listar()) > 0) {
        echo " " . listarArreglo($objViaje->listar());
        /* consultamos cual viaje se quiere modificar */
        echo "\n Ingrese el id de viaje del viaje que desee modificar \n";
        $idViaje = trim(fgets(STDIN));
        /* instanciamos un viaje con los atributos del viaje elegido */
        $objViaje->Buscar($idViaje);
        /* consultamos dentro de un bucle por las modificaciones y las seteamos a la instancia de viaje*/
        while ($bandera) {
            echo "\n Elija un numero segun lo que desee modificar:
           \n 1) Modificar destino:
           \n 2) Modificar maximo de pasajeros
           \n 3) Modificar Importe de viaje
           \n 4) Modificar El id Empresa 
           \n 5) Modificar el Responsable 
           \n 6) Finalizar Modificaciones \n";
            $eleccion = trim(fgets(STDIN));
            switch ($eleccion) {
                case 1:
                    echo "\n Ingrese nuevo destino: ";
                    $destinoV = trim(fgets(STDIN));
                    $objViaje->setDestino($destinoV);
                    break;
                case 2:
                    echo "\n Ingrese nuevo maximo de pasajeros: ";
                    $maxPasajeros = trim(fgets(STDIN));
                    $objViaje->setMaxPasajeros($maxPasajeros);
                    break;
                case 3:
                    echo "\n Ingrese nuevo Importe de viaje: ";
                    $importe = trim(fgets(STDIN));
                    $objViaje->setImporte($importe);
                    break;
                case 4:/* modificar la empresa*/
                    $objEmpresa = crearEmpresa();
                    $objViaje->setObjEmpresa($objEmpresa);
                    break;
                case 5:/* modificar el Responsable */
                    $objResponsable = crearResponsable();
                    $objViaje->setObjResponsable($objResponsable);
                    break;
                default:
                    $bandera = false;
                    break;
            }
        }
        /* insertamos el viaje modificado a la base de datos */
        $modPos = $objViaje->modificar();
        /* confirmamos el resutado */
        return $modPos;
    }
}
function eliminarViaje($idViaje) {
    $confirm = false;
    $objPasajero = new Pasajero;
    $objViaje = new Viaje;
    /* advertimos de la eliminacion de datos */
    echo "\n Esta seguro de eliminar el viaje con codigo: " . $idViaje . "? , la eliminacion conllevara a la eliminacion tambien del Responsable del viaje \n
    1)SI \n
    2)NO \n
    ";
    $eleccEliminar = trim(fgets(STDIN));
    if ($eleccEliminar == 1 || $eleccEliminar == "SI") {
        /* si se accedio a eliminar */
        /* instanciamos un viaje con los atributos del viaje elegido */
        $objViaje->Buscar($idViaje);
        $arrPasajeros = $objPasajero->listar("idviaje = ".$objViaje->getIdViaje());
        if((count($arrPasajeros)) > 0){
            /* si hay pasajeros cargados en el arreglo se indicara que no es posible */
            echo "\n No es posible eliminar el viaje ya que todavia hay ".count($arrPasajeros)." pasajeros cargados \n";
        }else{
             /* eliminamos el viaje */
        $confirm = $objViaje->eliminar();
        }
       
    }
    return $confirm;
}
function crearResponsable() {
    $objResponsable = new Responsable;
    /* solicitamos los datos del obj Responsable */
    echo "\n A CONTINUACION INGRESE LOS DATOS DEL RESPONSABLE DEL VIAJE \n";
    echo "\n Ingrese numero de licencia: ";
    $numLic = trim(fgets(STDIN));
    echo "\n Ingrese nombre de empleado: ";
    $nomb = trim(fgets(STDIN));
    echo "\n Ingrese apellido de empleado: ";
    $apell = trim(fgets(STDIN));

    /* cargamos los datos al objeto */
    $objResponsable->carga($nomb, $apell, $numLic);
    /* los insertamos en la bd */
    $result = $objResponsable->insertar();
    /* si la insersion fue positiva retornamos el objeto */

    return $objResponsable;
}
function crearPasajero($idViaje) {

    $bandera = true;
    $telefono = 0;
    /* creamos un obj vacio */
    $objPasajero = new Pasajero;
    while ($bandera) {
        /* solicitamos los datos del obj pasajero*/
        echo "\n Ingrese nombre del pasajero: ";
        $nombre = trim(fgets(STDIN));
        echo "\n Ingrese Apellido del pasajero: ";
        $apellido  = trim(fgets(STDIN));
        echo "\n Ingrese telefono del pasajero: ";
        $telefono  = trim(fgets(STDIN));
        echo "\n Ingrese numero de documento del pasajero: ";
        $documento = trim(fgets(STDIN));
        /* cargamos los datos al objeto  */
        $objPasajero->carga($idViaje, $nombre, $apellido, $telefono, $documento);
        /* los insertamos en la bd */
        if ($objPasajero->listar(" pdocumento = '" . $documento . "' && idviaje=" . $idViaje) != null) {
            echo "\n el pasajero ya se encuentra cargado\n";
        } else {
            $objPasajero->insertar();
            $bandera = false;
        }
    }
    /* retornamos el objeto */
    return $objPasajero;
}


/* Funcion añadir pasajeros */
function aniadirPasajeros($objViaje) {
    /* seteamos el numero de pasajeros asociados al viaje en el array interno de pasajeros del objeto */
    $colPasajeros = array();
    $bandera = true;
    $espDiponible = 0;
    /* se ejecuta un bucle  */
    while ($bandera) {
        if (count($objViaje->getArrayPasajeros()) < $objViaje->getMaxPasajeros()) {
            $espDiponible = $objViaje->getMaxPasajeros() - count($objViaje->getArrayPasajeros());
            echo "\n Cantidad de pasajeros: " . count($objViaje->getArrayPasajeros()) . " \n
        Asientos disponibles: " . $espDiponible . "\ n
        Desea añadir Pasajero? \n
        1)SI \n
        2)NO \n";
            $resp = trim(fgets(STDIN));
            if ($resp == 1) {
                /* si se elige añadir otro pasajero */
                $objPasajero = crearPasajero($objViaje->getIdViaje());
                /* se añade el pasajero a la coleccion del viaje */
                $colPasajeros = $objViaje->getArrayPasajeros();
                $colPasajeros[] = $objPasajero;
                $objViaje->setArrayPasajeros($colPasajeros);
                echo "\n El Pasajero se añadio con los siguientes datos: \n" . $objPasajero;
            } else {
                /* No se desan añadir mas pasajeros */
                $bandera = false;
            }
        } else {
            /* no hay espacio para mas pasajeros */
            echo "\n No hay espacio para mas pasajeros, Maximo alcanzado: " . count($objViaje->getArrayPasajeros()) . " Pasajeros para un maximo de " . $objViaje->getMaxPasajeros() . " Espacios";
            $bandera = false;
        }
    }
}
function eliminarPasajeros($arrPasajeros){
    /* buscamos dentro del viaje la coleccion de pasajeros */
    $pasajero = new Pasajero;
    $result = false;
    /* recorremos el arreglo de pasajeros del viaje */
    foreach($arrPasajeros as $pasajero){
        echo "\n Desea eliminar el siguiente pasajero? \n";
        echo " ".$pasajero;
        echo "\n 1)SI \n
                 2) NO \n";
        $opElim = trim(fgets(STDIN));
        if($opElim == 1){
            $arrayElim[]=$pasajero;
        }
    }
    if(count($arrayElim)>0){
        foreach($arrayElim as $pas){
           $result = $pas->eliminar();
           if($result = true){
            echo "\n Se ha eliminado correctamente \n";
           }
        }
        return $result;

    }
    
    


}
