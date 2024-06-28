<?php
include_once 'BaseDatos.php';
class Empresa {
    private $id;
    private $nombre;
    private $direccion;
    private $mensajeOperacion;

    /* constructor */
    public function __construct() {
        $this->id = 0;
        $this->nombre = "";
        $this->direccion = "";
    }

    /* cargar datos */
    public function carga($nombre, $direccion) {
        $this->setNombre($nombre);
        $this->setDireccion($direccion);
    }

    /* setters */
    public function setId($id) {
        $this->id = $id;
    }
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }
    public function setMensajeOperacion($mensajeOperacion) {
        $this->mensajeOperacion = $mensajeOperacion;
    }

    /* getters */
    public function getId() {
        return $this->id;
    }
    public function getNombre() {
        return $this->nombre;
    }
    public function getDireccion() {
        return $this->direccion;
    }
    public function getMensajeOperacion() {
        return $this->mensajeOperacion;
    }
    /* Funciones De Consultas */
    /* segun un idEmpresa que ingresa por parametro busca el registro en la base de datos y setea los atributos del objeto que lo invoca con dichos registros */
    public function Buscar($idEmpresa) {
        $base = new BaseDatos;
        $consultaEmpresa = "SELECT * FROM empresa WHERE idempresa=" . $idEmpresa;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaEmpresa)) {
                if ($row2 = $base->Registro()) {
                    $this->setId($idEmpresa);
                    $this->setNombre($row2['enombre']);
                    $this->setDireccion($row2['edireccion']);
                    $resp = true;
                }
            } else $this->setmensajeoperacion($base->getError());
            
        } else {
            $this->setmensajeoperacion($base->getError());
        }

        return $resp;
    }

    /* funcion listar: realiza una busqueda de todos los registros de la tabla empresa, los filtra en caso de alguna condicion alterntativa y los devuelve en un arreglo indexado*/
    public function listar($condicion = "") {
        $arregloEmpresa = null;
        $base = new BaseDatos();
        $consultaEmpresa = "SELECT * FROM empresa";
        /* se verifica si hay alguna condicion para añadir a la consulta */
        if ($condicion != "") {
            $consultaEmpresa .= ' WHERE ' . $condicion;
        }
        $consultaEmpresa .= " order by enombre";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaEmpresa)) {
                $arregloEmpresa = array();
                while ($row2 = $base->Registro()) {
                    $id = $row2['idempresa'];
                    $nombre = $row2['enombre'];
                    $direccion = $row2['edireccion'];

                    $empresa = new Empresa();
                    $empresa->carga($nombre, $direccion);
                    $empresa->setId($id);
                    array_push($arregloEmpresa, $empresa);
                }
            } else {
                $this->setmensajeoperacion($base->getError());
               
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $arregloEmpresa;
    }

    /* Funcion Insertar empresa: Inserta los valores instanciados en el obj en la base de datos y obtiene el id con el que se guardo asignandolo al atribudo Id del objeto*/

    public function Insertar() {
        $base = new BaseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO empresa(enombre, edireccion) ";
        $consultaInsertar .= "VALUES ('" . $this->getNombre() . "', '" . $this->getDireccion() . "')";
        if ($base->Iniciar()) {
            if ($id = $base->devuelveIDInsercion($consultaInsertar)) {
                $this->setId($id);
                $resp = true;
            } else {
                $this->setmensajeoperacion($base->getError());
            
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

    /* Funcion Modificar: carga los datos del objeto en el registro que coincida con la id del objeto */
    public function modificar() {
        $resp = false;
        $base = new BaseDatos();
        $consultaModifica = "UPDATE empresa SET ";
        $consultaModifica .= "enombre = '" . $this->getNombre() . "', ";
        $consultaModifica .= "edireccion = '" . $this->getDireccion() . "' ";
        $consultaModifica .= "WHERE idempresa = " . $this->getId();

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaModifica)) {
                $resp =  true;
            } else {
                $this->setmensajeoperacion($base->getError());
               
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }


    public function eliminar() {
        $base = new BaseDatos();
        $resp = false;
        if ($base->Iniciar()) {
            $consultaBorra = "DELETE FROM empresa WHERE idempresa=" . $this->getId();
            if ($base->Ejecutar($consultaBorra)) {
                $resp =  true;
            } else {
                $this->setmensajeoperacion($base->getError());
                
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

    /* funcion toString */
    public function __toString() {
        $datosEmpresa = "";
        $datosEmpresa .= "\n ID Empresa: " . $this->getId();
        $datosEmpresa .= "\n Nombre Empresa: " . $this->getNombre();
        $datosEmpresa .= "\n Direccion: " . $this->getDireccion() . "\n";
        return $datosEmpresa;
    }
}
