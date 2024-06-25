<?php
include_once "BaseDatos.php";
include_once "Viaje.php";
class Pasajero{
    /* Atributos */
    
    private $documento;
    private $nombre;
    private $apellido;
    private $telefono;
    private $objViaje;
    private $mensajeOperacion;

    /* Metodo construct */
    public function __construct()
    {
        $this->objViaje = new Viaje();
        $this->nombre= "";
        $this->apellido= "";
        $this->telefono = 0;
        $this->documento = "";
    }
     /* metodo carga */

     public function carga($idViaje, $nombre, $apellido, $telefono, $documento){
        $objViaje = new Viaje();
        $result = $objViaje->Buscar($idViaje);
        if($result){
            $this->setObjViaje($objViaje);
            $this->setNombre($nombre);
            $this->setApellido($apellido);
            $this->setTelefono($telefono);
            $this->setDocumento($documento);

        }
        return $result;
     }
    /* Metodos setters */

    public function setObjViaje($objViaje){
        $this->objViaje = $objViaje;

    }
    public function setNombre($nombre){
        $this->nombre = $nombre;
    }
    public function setApellido($apellido){
        $this->apellido = $apellido;
    }
    public function setTelefono($telefono){
        $this->telefono = $telefono;
    }
    public function setDocumento($documento){
        $this->documento = $documento;
    }
    public function setMensajeOperacion($mensajeOperacion){
        $this->mensajeOperacion = $mensajeOperacion;
    }
    /* Metodos getters */
    public function getObjViaje(){
        return $this->objViaje;
    }
    public function getNombre(){
        return $this->nombre;
    }
    public function getApellido(){
        return $this->apellido;
    }
    public function getTelefono(){
        return $this->telefono;
    }
    public function getDocumento(){
        return $this->documento;
    }
    public function getMensajeOperacion(){
        return $this->mensajeOperacion;
    }

    /**
	 * Recupera los datos de un pasajero por dni
	 * @param int $dni
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function Buscar($dni){
		$base=new BaseDatos();
        /* se genera la consulta en base al parametro documento */
		$consultaPasajero="Select * FROM pasajero WHERE pdocumento='".$dni."'";
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPasajero)){
                /* si se ejecuta la consulta se busca en el registro */
				if($row2=$base->Registro()){
                    /* se setean los atributos en base a los registros del arreglo indexado obtenido */
                    $objViaje = new Viaje;
                    $resp = $objViaje->Buscar($row2['idviaje']);
                    $this->setObjViaje($objViaje);
                    $this->setDocumento($dni);
                    $this->setNombre($row2['pnombre']);
                    $this->setApellido($row2['papellido']);
                    $this->setTelefono($row2['ptelefono']);
				}				
			
		 	}	else {
		 			$this->setMensajeOperacion($base->getError());
		 		
			}
		 }	else {
            $this->setMensajeOperacion($base->getError());
		 	
		 }		
		 return $resp;
	}	

    /* Funcion Listar: Realiza una busqueda de todos los registros de la tabla, en caso de recibir una condicion por parametro la usara para filtrar de entre todos lor registros y finalmente devolvera un arreglo indexado con objetos tipo pasajero */
    public function listar($condicion=""){
	    $arregloPasajeros = null;
		$base=new BaseDatos();
		$consultaPasajero="Select * FROM pasajero ";
		if ($condicion!=""){
		    $consultaPasajero.=' WHERE '.$condicion;
		}
        $consultaPasajero .= "ORDER BY papellido";
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPasajero)){			
				$arregloPasajeros= array();
               /* Mientras haya registros en la tabla cuando el puntero avance */
				while($row2=$base->Registro()){
                    /* se obtienen los datos del registro actual */
                    $nombre= $row2['pnombre'];
                    $apellido = $row2['papellido'];
                    $telefono = $row2['ptelefono'];
                    $idViaje=$row2['idviaje'];
                    $nroDoc = $row2['pdocumento'];		
                    /* se crea un objeto pasajero usando como atributos los datos listados */	    
					$pasaj=new Pasajero();
					$pasaj->carga($idViaje, $nombre, $apellido,$telefono, $nroDoc);
                    /* se carga el objeto creado a una coleccion de pasajeros */
					array_push($arregloPasajeros,$pasaj);
	
				}
				
			
		 	}	else {
		 			$this->setMensajeOperacion($base->getError());
		 		
			}
		 }	else {
            $this->setMensajeOperacion($base->getError());
		 	
		 }	
		 return $arregloPasajeros;
	}

    /* Inserta las instancias del objeto persona como registro en la tabla persona y obtiene el id con el que se registro asignandolo al atributo id */
     public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		$consultaInsertar="INSERT INTO pasajero(pdocumento, pnombre, papellido, ptelefono, idviaje) 
				VALUES ('".$this->getDocumento()."','".$this->getNombre()."','".$this->getApellido()."',".$this->getTelefono().",".$this->getObjViaje()->getIdViaje().")";
		
		if($base->Iniciar()){

			if($base->Ejecutar($consultaInsertar)){
                /* si se ejecuto la insercion con exito */
			    $resp=true;

			}	else {
					$this->setmensajeoperacion($base->getError());
					
			}

		} else {
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp;
	}
 
/* Modifica los datos del registro de la tabla pasajero cuyo dni coincida con el instanciado en el objeto */
 public function modificar(){
    $resp =false; 
    $base=new BaseDatos();
    /* se crea el codigo de actualizacion usando como parametro el atributo clave de documento */
    $consultaModifica="UPDATE pasajero SET pnombre='".$this->getNombre()."',papellido='".$this->getApellido()."', ptelefono=".$this->getTelefono()." WHERE pdocumento = ".$this->getDocumento()." && idviaje = ".$this->getObjViaje()->getIdViaje()." ";
    if($base->Iniciar()){
        if($base->Ejecutar($consultaModifica)){
            /* si se ejecuto la modificacion con exito */
            $resp=  true;
        }else{
            $this->setmensajeoperacion($base->getError());
            
        }
    }else{
            $this->setmensajeoperacion($base->getError());
        
    }
    return $resp;
}
/* Elimina el registro de la tabla que coincida con el dni y retorna true en caso de conseguirlo */
public function eliminar(){
    $base=new BaseDatos();
    $resp=false;
    if($base->Iniciar()){
            $consultaBorra="DELETE FROM pasajero WHERE pdocumento='".$this->getDocumento()."'";
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


    /* recibe por parametro el nombre del atributo a modificar y la nueva instancia por la que se reemplazaria retornando un booleano de confirmacion */
    public function modificarPasajero($atributo, $nuevaInstancia){
        switch($atributo){
            /* modificar nombre */
            case 1: $this->setNombre($nuevaInstancia);
            $confirmacion= true;
                 break;
             /* modificar apellido */
            case 2: $this->setApellido($nuevaInstancia);
            $confirmacion= true;
                break;
            /* modificar documento */
            case 3: $this->setDocumento($nuevaInstancia);
            $confirmacion= true;
                break; 
            /* Modificar numero de telefono */
             case 4: $this->setTelefono($nuevaInstancia);
             $confirmacion= true;
                break;
            default: $confirmacion= false;
            break;         
        }
        return $confirmacion;

    }
    /* metodo toString */
    public function __toString()
    {
        $datosPasajero = "";
        $datosPasajero .= "\n Documento: ".$this->getDocumento();
        $datosPasajero .= "\n Nombre: ".$this->getNombre();
        $datosPasajero .= "\n Apellido: ".$this->getApellido();
        $datosPasajero .= "\n Telefono: ".$this->getTelefono();
        $datosPasajero .="\n Id Viaje: ".$this->getObjViaje()->getIdViaje()."\n";

        return $datosPasajero;
    }
}