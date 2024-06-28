<?php
include_once "../Datos/BaseDatos.php";
include_once "../Datos/Viaje.php";
include_once "../Datos/Responsable.php";
include_once "../Datos/Pasajero.php";
include_once "../Datos/Empresa.php";
/* Inicializacion de var */

$pasajero1 = new Pasajero;
$pasajero2 = new Pasajero;
$pasajero3 = new Pasajero;
$pasajero4 = new Pasajero;

/* creamos un par de objs de Responsables */
$resp1 = new Responsable();
$resp2 = new Responsable();
/* creamos un par de objs de Empresa */
$empresa1 = new Empresa();
$empresa2 = new Empresa();
/* creamos un par de objs de Empresa */
$viaje = new Viaje();
$viaje2 = new Viaje();
$confirm = eliminarEmpresa();
if ($confirm) {
  echo "\n La empresa se elimino de la base de datos \n";
} else {
  echo "\n La empresa no pudo ser elminada \n";
}

/* se instancian varios objetos tipo responsable*/
/* $idempresa1 =0;
$idempresa2= 0;
$resp1->carga("ricardo", "corazonDLeon", 4567);
$resp2->carga("Tincho", "quintanga", 8324);  */
/* se instertan a la base de datos */
/* $resp1->insertar();
$resp2->insertar();  */
/* 
$numEmp1 = $resp1->getNumEmpleado();
$numEmp2 = $resp2->getNumEmpleado(); */

/* $resp1->carga("Fabian", "Marino", 5643);
$resp2->carga("Alicia", "Quiroz", 3468); 
$resp1->insertar();
$resp2->insertar();  */
//EMPRESA 1
/* $empresa1->carga("SYP", "Villa polonio");
$empresa->Insertar();
$idempresa = $empresa->getId(); */
//EMPRESA 2
/* $empresa2->carga("Tamarindo", "chosmalal");
$empresa->Insertar();
$idempresa2 = $empresa->getId(); */

/* $viaje->carga("Formosa", 12, 3, 5, 6331);
$viaje2->carga("Cipolletti", 5, 4, 6, 8456); 

$viaje->setDestino("Villa Tilcara");
$viaje->setMaxPasajeros(45);
$viaje->modificar(); */
/* $viaje->Buscar(4);
$viaje->eliminar();
  echo "\n". $viaje."\n"; */
function listarArreglo($arregloDeObjetos) {
  $listado = "";
  foreach ($arregloDeObjetos as $obj) {
    $listado .= " " . $obj;
  }
  return $listado;
}

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

  
  

/* $result = $viaje->insertar();
$result = $viaje2->insertar(); */
//PASAJEROS
/* $pasajero1->carga(1, "fabian", "hernandez", 23942094, 312839128);
$pasajero2->carga(1, "Gerardo", "Fonzeca", 234203402, 123129874);
$pasajero3->carga(2, "Fernanda", "hernandez", 23423453, 234293842);
$pasajero4->carga(2, "Adriana", "Fica", 234255645, 234234567);

$pasajero1->insertar();
$pasajero2->insertar();
$pasajero3->insertar();
$pasajero4->insertar(); */


/* echo "\n se han OBTENIDO de la base de datos los datos de: \n".$resp1."\n //////////////////////////////////////////////////////////////// \n".$resp2; */

/* if($result){
  echo "\n Se han insertado con exito los datos de viaje \n";
} */
