<?php
include_once "BaseDatos.php";
class Responsable{
    /* atributos */
    private $numEmpleado;
    private $numLicencia;
    private $nombre;
    private $apellido;  
    private $mensajeOperacion;

    /* Metodo construct */
    public function __construct() {
        $this->numEmpleado = 0;
        $this->numLicencia = 0;
        $this->nombre="";
        $this->apellido="";
    }
    /* metodo carga */
    public function carga($nombre, $apellido, $numLicencia) {
        $this->setNumLicencia($numLicencia);
        $this->setNombre($nombre);
        $this->setApellido($apellido);
        /* El atributo de numEmpleado se obvia ya que es autoIncrement y estaria determinado x la bd */
    }
    /* Metodos setters */
    public function setNumEmpleado($numEmpleado) {
        $this->numEmpleado = $numEmpleado;
    }
    public function setNumLicencia($numLicencia) {
        $this->numLicencia = $numLicencia;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }
    public function setMensajeOperacion($mensajeOperacion) {
        $this->mensajeOperacion = $mensajeOperacion;
    }
    /* Metodos getters */
    public function getNumEmpleado() {
        return $this->numEmpleado;
    }
    public function getNumLicencia() {
        return $this->numLicencia;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getApellido()
    {
        return $this->apellido;
    }
    public function getMensajeOperacion() {
        return $this->mensajeOperacion;
    }



    /**
     * Recupera los datos de un responsable por su numero de empleado
     * @param int $numEmpleado
     * @return true en caso de encontrar los datos, false en caso contrario 
     */
    public function Buscar($numEmpleado) {
        $base = new BaseDatos();
        /* se crea el sql de busqueda en base al numEmpleado */
        $consultaResponsable = "Select * from responsable where rnumeroempleado=" . $numEmpleado;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaResponsable)) {
                if ($row2 = $base->Registro()) {
                    /* setea los atributos de la clase con los obtenidos x la base de datos */
                    $this->setNumLicencia($row2['rnumerolicencia']);
                    $this->setNumEmpleado($numEmpleado);
                    $this->setNombre($row2['rnombre']);
                    $this->setApellido($row2['rapellido']);
                    $resp = true;
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }



    /* Funcion Listar: Crea una consulta general y añade una condicion de busqueda (en caso de que se aplique una) para filtrar la busqueda y terminar entregando un arreglo indexado con objetos tipo responsable que cumplan con los criterios de la consulta formada */
    public function listar($condicion = "") {
        $arregloResponsable = null;
        $base = new BaseDatos();
        $consultaResponsables = "Select * from responsable ";
        /* se verifica si hay alguna condicion para agregar */
        if ($condicion != "") {
            $consultaResponsables .= ' where ' . $condicion;
        }
        /* añade que los registros se ordenen segun el apellido */
        $consultaResponsables .= " order by rapellido ";
        //echo $consultaPersonas;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaResponsables)) {
                $arregloResponsable = array();
                while ($row2 = $base->Registro()) {
                    /* dentro de row2 se almacena un registro convertido en array asociativo con los nombres de cada columna de la tabla Responsable */
                    $numEmpleado = $row2['rnumeroempleado'];
                    $numLicencia = $row2['rnumerolicencia'];
                    $Nombre = $row2['rnombre'];
                    $Apellido = $row2['rapellido'];
                    /* se cargan los datos obtenidos del array en un obj responsable */
                    $respons = new Responsable();
                    $respons->carga($Nombre, $Apellido, $numLicencia);
                    $respons->setNumLicencia($numEmpleado);
                    /* el objeto cargado se añade a una coleccion de objetos Responsable */
                    array_push($arregloResponsable, $respons);
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        /* se retorna una coleccion de obj responsable o null */
        return $arregloResponsable;
    }


    /* funcion insertar: genera una consulta que Inserta los atributos comunes (no clave) a la base de datos y obtiene y asigna el id autoincremental con el que se guardo en la bd al atributo clave (numEpleado) de la clase*/
    public function insertar() {
        $base = new BaseDatos();
        $resp = false;
        /* se genera la consulta para modificar los dos atributos no claves de la tabla */
        $consultaInsertar = "INSERT INTO responsable(rnumerolicencia, rnombre, rapellido) 
				VALUES (" . $this->getNumLicencia(). ",'".$this->getNombre()."','".$this->getApellido()."')";

        if ($base->Iniciar()) {
           /* si se consiguio iniciar la bd se realizara la consulta obteniendo el idAutogenerado */
            if ($numEmpleado = $base->devuelveIDInsercion($consultaInsertar)) {
                /* se trae el numEmpleado generado en la bd y se setea localmente como atributo del obj */
                $this->setNumEmpleado($numEmpleado);
                $resp = true;
            } else {
               $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }
    /* Funcion modificar: Actualiza los datos del registro que coincida con el numero de empleado en la tabla y le asigna las instancias cargadas en el objeto */

    public function modificar() {
        $resp = false;
        $base = new BaseDatos();
        /* se genera la consulta en base a los atributos cargados en la clase */
        $consultaModifica = "UPDATE responsable SET 'rnumerolicencia='" . $this->getNumLicencia() .",'rnombre'=".$this->getNombre().",'rapellido'=".$this->getApellido(). " WHERE rnumeroempleado" . $this->getNumEmpleado();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaModifica)) {
                /* En caso de que se inicie y ejecute correctamente se confirmara con el boolean */
                $resp =  true;
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

    /* Elimina el registro de la bd que coincida con el numero de empleado instanciado en el objeto tipo Responsable y devolvera false en caso de no conseguirlo */
    public function eliminar() {
        $base = new BaseDatos();
        $resp = false;
        if ($base->Iniciar()) {
            /* se genera el sql de eliminacion */
            $consultaBorra = "DELETE FROM responsable WHERE rnumeroempleado=" . $this->getNumEmpleado();
            if ($base->Ejecutar($consultaBorra)) {
                /* si se elimina el registro correctamente */
                $resp =  true;
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

    /* Metodo toString */
    public function __toString() {
        $datosResp = "";
        $datosResp .= "\n Nombre: " . $this->getNombre();
        $datosResp .= "\n Apellido: " . $this->getApellido();
        $datosResp .= "\n Numero de empleado: " . $this->getNumEmpleado();
        $datosResp .= "\n Numero de licencia: " . $this->getNumLicencia() . "\n";
        return $datosResp;
    }
}
