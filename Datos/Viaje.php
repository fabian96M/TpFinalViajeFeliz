<?php
include_once "BaseDatos.php";
include_once "Empresa.php";
include_once "Responsable.php";
include_once "Pasajero.php";
class Viaje
{
    private $idViaje;
    private $destino;
    private $maxPasajeros;
    private $objEmpresa;
    private $objResponsable;
    private $importe;
    private $arrayPasajeros;
    private $mensajeOperacion;
    /* Metodo constructor */
    public function __construct()
    {
        $this->idViaje = 0;
        $this->destino = "";
        $this->maxPasajeros = 0;
        $this->objEmpresa = new Empresa();
        $this->objResponsable = new Responsable();
        $this->importe = 0;
        $this->arrayPasajeros = [];
    }
    /* metodo carga */
    public function carga( $destino, $maxPasajeros, $idEmpresa, $numEmpleado, $importe){
        $confirmacion = false;
        $objResponsable = new Responsable();
        $objEmpresa = new Empresa();
        /* se realiza la busqueda de los objetos segun su clave */
        $conf1 = $objResponsable->Buscar($numEmpleado);
        $conf2 = $objEmpresa->Buscar($idEmpresa);
        /* si ambas busquedas fueron positivas */
        if($conf1 && $conf2){
            /* se cargan los atributos al objeto  */
            $this->setObjEmpresa($objEmpresa);
            $this->setObjResponsable($objResponsable);
            $this->setDestino($destino);
            $this->setMaxPasajeros($maxPasajeros);
            $this->setImporte($importe);
            /* se confirmara que la carga fue exitosa */
            $confirmacion = true;
        }
        return $confirmacion;
       /*  $objPasajero = new Pasajero; */

        /* se obvia el idViaje al ser un atributo a definir por la bd */
        
        /* $this->setArrayPasajeros($objPasajero->listar('idviaje='.$this->getIdViaje())); */
    }
    /* Metodos Setters */
    public function setIdViaje($idViaje)
    {
        $this->idViaje = $idViaje;
    }
    public function setDestino($destino)
    {
        $this->destino = $destino;
    }
    public function setMaxPasajeros($maxPasajeros)
    {
        $this->maxPasajeros = $maxPasajeros;
    }
    public function setObjEmpresa($objEmpresa){
        $this->objEmpresa = $objEmpresa;
    }
    public function setObjResponsable($objResponsable)
    {
        $this->objResponsable = $objResponsable;
    }
    public function setImporte($importe){
        $this->importe = $importe;
    }
    public function setArrayPasajeros($arrayPasajeros){
        $this->arrayPasajeros = $arrayPasajeros;
    }
    public function setMensajeOperacion($mensajeOperacion){
        $this->mensajeOperacion = $mensajeOperacion;
    }
    /* metodos Getters */
    public function getIdViaje()
    {
        return $this->idViaje;
    }
    public function getDestino()
    {
        return $this->destino;
    }
    public function getMaxPasajeros()
    {
        return $this->maxPasajeros;
    }
    public function getObjEmpresa()
    {
        return $this->objEmpresa;
    }
    public function getObjResponsable()
    {
        return $this->objResponsable;
    }
    public function getImporte(){
        return $this->importe;
    }
    public function getArrayPasajeros(){
        return $this->arrayPasajeros;
    }
    public function getMensajeOperacion(){
        return $this->mensajeOperacion;
    }

    /* Metodos Especiales */

    /* Metodos de Base Datos */

    /**
	 * Recupera los datos de un viaje por su idViaje
	 * @param int $idViaje
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function Buscar($idViaje){
		$base=new BaseDatos();
		$consultaViaje="Select * FROM viaje WHERE idviaje=".$idViaje;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaViaje)){
				if($row2=$base->Registro()){
                    $objResponsable = new Responsable;
                    $empresa = new Empresa;
                    /* se buscan los atributos clave de cada obj y se guarda una confirmacion x cada busqueda */
                    $resp1 = $empresa->Buscar($row2['idempresa']);
                    $resp2 = $objResponsable->Buscar($row2['rnumeroempleado']);
                    /* se definen los atributos locales de la clase */
				    $this->setIdViaje($idViaje);
				    $this->setDestino($row2['vdestino']);
					$this->setMaxPasajeros($row2['vcantmaxpasajeros']);
					$this->setObjEmpresa($empresa);
					$this->setObjResponsable($objResponsable);
                    $this->setImporte($row2['vimporte']);
                    /* si las busquedas de ambos obj fueron exitosas */
                    if($resp1 && $resp2){
                        $resp= true;
                    }
				}				
			
		 	}	else {
		 			$this->setMensajeOperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setMensajeOperacion($base->getError());
		 	
		 }		
		 return $resp;
	}	

/* Lista los registros de la tabla viaje que cumplan con una condicion ingresada por parametro, en caso de no ingresar condicion listara todos los registros. Al final devolvera un arreglo indexado de objetos viaje */
    public function listar($condicion=""){
	    $arregloViaje = null;
		$base=new BaseDatos();
		$consultaViaje="Select * FROM viaje ";
		if ($condicion!=""){
		    $consultaViaje.=' WHERE '.$condicion;
		}
		$consultaViaje.=" order by vdestino";
		//echo $consultaPersonas;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaViaje)){				
				$arregloViaje= array();
				while($row2=$base->Registro()){
                    /* se guardan los registros entregads en el arreglo $row2 */
				    $id=$row2['idviaje'];
					$destino=$row2['vdestino'];
					$maxPasajeros=$row2['vcantmaxpasajeros'];
					$idEmpresa=$row2['idempresa'];
					$responsableV=$row2['rnumeroempleado'];
                    $importe = $row2['vimporte'];

                    /* se crea y añade cada dato al viaje */
					$viaje=new Viaje();
					$viaje->carga($destino, $maxPasajeros, $idEmpresa, $responsableV, $importe);
                    $viaje->setIdViaje($id);
                    /* se añade el viaje al arreglo de viajes */
					array_push($arregloViaje,$viaje);
	
				}
				
			
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());
		 	
		 }	
		 return $arregloViaje;
	}	

    /* Crea una consulta de insersion con las instancias del objeto y devuelve true si consigue instertar el registro con exito */
    public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		$consultaInsertar=" INSERT INTO viaje(vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte) VALUES ('".$this->getDestino()."',".$this->getMaxPasajeros().",".$this->getObjEmpresa()->getId().",".$this->getObjResponsable()->getNumEmpleado().",".$this->getImporte().")";
		
		if($base->Iniciar()){

			if($id = $base->devuelveIDInsercion($consultaInsertar)){
                $this->setIdViaje($id);
			    $resp=  true;

			}	else {
					$this->setmensajeoperacion($base->getError());
					
			}

		} else {
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp;
	}


    /* Ejecuta una consulta sobre la bd que busca el registro segun la id y lo actualiza con las instancias cargadas en el objeto viaje */
    public function modificar(){
	    $resp =false; 
	    $base=new BaseDatos();
		$consultaModifica="UPDATE viaje SET vdestino='".$this->getDestino()."',vcantmaxpasajeros=".$this->getMaxPasajeros()." WHERE idviaje = " .$this->getIdViaje()."&& idempresa = ".$this->getObjEmpresa()->getId()."&& rnumeroempleado = ". $this->getObjResponsable()->getNumEmpleado()." ";
		if($base->Iniciar()){
			if($base->Ejecutar($consultaModifica)){
			    $resp=  true;
			}else{
				$this->setmensajeoperacion($base->getError());
				
			}
		}else{
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp;
	}

    /* Ejecuta un comando de eliminacion sobre la bd eliminando el registro que posea la clave primaria instanciada en el objeto viaje */

    public function eliminar(){
		$base=new BaseDatos();
		$resp=false;
		if($base->Iniciar()){
				$consultaBorra="DELETE FROM viaje WHERE idviaje=".$this->getIdViaje();
				if($base->Ejecutar($consultaBorra)){
				    $resp=  true;
				}else{
						$this->setmensajeoperacion($base->getError());
					
				}
		}else{
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp; 
	}



 /*    public function arregloTexto($arreglo){
        $datosArr = "";
        foreach($arreglo as $elemento){
            $datosArr .= "\n ".$elemento; 
        }
        return $datosArr;
    } */

    /* Metodo toString */
    public function __toString()
    {
        $idEmpresa = $this->getObjEmpresa()->getId();
        $datosViaje= "";
        $datosViaje .= "\n Codigo de Viaje: " . $this->getIdViaje();
        $datosViaje .= " \n Destino: " . $this->getDestino();
        $datosViaje .= " \n Maximo de Pasajeros: " . $this->getMaxPasajeros();
        $datosViaje .= " \n ID Empresa: ".$idEmpresa;
        $datosViaje .= " \n Responsable (Codigo de Empleado):" . $this->getObjResponsable()->getNumEmpleado();
        $datosViaje .= "\n Importe: ".$this->getImporte();
        return $datosViaje;
    }
}
