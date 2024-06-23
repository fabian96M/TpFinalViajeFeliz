<?php
/* IMPORTANTE !!!!  Clase para (PHP 5, PHP 7)*/
/* Inicializamos los datos correspondientes a la base de datos */
class BaseDatos {
    private $HOSTNAME;
    private $BASEDATOS;
    private $USUARIO;
    private $CLAVE;
    private $CONEXION;
    private $QUERY;
    private $RESULT;
    private $ERROR;
    /**
     * Constructor de la clase que inicia ls variables instancias de la clase
     * vinculadas a la coneccion con el Servidor de BD
     */
    public function __construct() {
        $this->HOSTNAME = "127.0.0.1";
        $this->BASEDATOS = "bdviajes";
        $this->USUARIO = "root";
        $this->CLAVE = "";
        $this->RESULT = 0;
        $this->QUERY = "";
        $this->ERROR = "";
    }
    /**
     * Funcion que retorna una cadena
     * con una peque�a descripcion del error si lo hubiera
     *
     * @return string
     */
    public function getError() {
        return "\n" . $this->ERROR;
    }

    /**
     * Inicia la coneccion con el Servidor y la  Base Datos Mysql.
     * Retorna true si la coneccion con el servidor se pudo establecer y false en caso contrario
     *
     * @return boolean
     */
    public  function Iniciar() {
        $resp  = false;
        /* se crea la bd usando la funcion mysqli_connect que es un alias de mysqli::__construct y usando tambien como parametros los primeros 4 atributos de la clase*/
        $conexion = mysqli_connect($this->HOSTNAME, $this->USUARIO, $this->CLAVE, $this->BASEDATOS);
        /* si la conexion tuvo exito */
        if ($conexion) {
            /*Se Selecciona la base de datos por defecto para realizar las consultas */
            if (mysqli_select_db($conexion, $this->BASEDATOS)) {
                /* se asigna la conexion al atributo conexion de la clase */
                $this->CONEXION = $conexion;
                /* por medio de la variable unset se  Destruye una o más variables especificadas */
                unset($this->QUERY);
                unset($this->ERROR);
                /* se modifica la variable a retornar al final de la funcion */
                $resp = true;
            } else {
                /* en caso de que no se consiga definir la bd se almacenara un mensaje de error en la clase */
                $this->ERROR = mysqli_errno($conexion) . ": " . mysqli_error($conexion);
            }
            /* en caso de que no se consiga la conexion se almacenara el ultimo error de conexion en el atributo error en la clase */
        } else {
            $this->ERROR =  mysqli_connect_errno($conexion) . ": " . mysqli_connect_error($conexion);
        }
        /* se retornara true si hubo exito */
        return $resp;
    }

    /**
     * Ejecuta una consulta en la Base de Datos.
     * Recibe la consulta en una cadena enviada por parametro.
     *
     * @param string $consulta
     * @return boolean
     */
    public function Ejecutar($consulta) {
        $resp  = false;
        unset($this->ERROR);
        /* se guarda la consulta localmente en la clase */
        $this->QUERY = $consulta;
        /* realizamos la consulta a la base de datos y la almcena en el atributo RESULT de la clase*/
        if ($this->RESULT = mysqli_query($this->CONEXION, $this->QUERY)) {
            /*Caso positivo: se cambia la variable para confirmar la consulta */
            $resp = true;
        } else {
            /* Caso Negativo: se almacena el ultimo error en el atributo ERROR de la clase */
            $this->ERROR = mysqli_errno($this->CONEXION) . ": " . mysqli_error($this->CONEXION);
        }
        /* se retorna el booleano que confirma el exito de la operacion */
        return $resp;
    }

    /**
     * Devuelve un registro retornado por la ejecucion de una consulta
     * el puntero se despleza al siguiente registro de la consulta
     *
     * @return boolean
     */
    public function Registro() {
        $resp = null;
        /*se verifica tener una instancia valida de resultado de consulta y que la conexion este presente */
        if (($this->RESULT instanceof mysqli_result && $this->CONEXION)) {
            /* se vacia el atributo ERROR */
            unset($this->ERROR);
            /* se obtiene una fila de resultado (de la ultima consulta) y se convierte a un array asociativo (con los nombres de columna) para asignarlo a la variable $temp */
            if ($temp = mysqli_fetch_assoc($this->RESULT)) {
                /* se asigna el resultado de temp a $resp en caso positivo para retornarlo al final */
                $resp = $temp;
            } else {
                /* se Libera la memoria asociada al resultado */
                mysqli_free_result($this->RESULT);
            }
        } else {
            /* se almacenan descirpcion y errores de conexion */
            $this->ERROR = mysqli_errno($this->CONEXION) . ": " . mysqli_error($this->CONEXION);
        }
        /* retorna un array asociativo (nombre columna) o null */
        return $resp;
    }

    /**
     * Devuelve el id de un campo autoincrement utilizado como clave de una tabla
     * Retorna el id numerico del registro insertado, devuelve null en caso que la ejecucion de la consulta falle
     *
     * @param string $consulta
     * @return int id de la tupla insertada
     */
    public function devuelveIDInsercion($consulta) {
        $resp = null;
        /* se vacia el atributo de error */
        unset($this->ERROR);
        /* se almacena la consulta en el atributo QUERY */
        $this->QUERY = $consulta;
        /* se realiza la consulta a la base de datos y se la almcena en el atributo RESULT de la clase*/
        if ($this->RESULT = mysqli_query($this->CONEXION, $this->QUERY)) {
            /* Devuelve el id autoincrement (o autogenerado) que se utilizó en la última consulta y lo asigna a $id */
            $id = mysqli_insert_id($this->CONEXION);
            /* almacena el id obtenido en la variable a retornar */
            $resp =  $id;
        } else {
            /* se almacenara el nombre y descripcion del error en el atributo ERROR */
            $this->ERROR = mysqli_errno($this->CONEXION) . ": " . mysqli_error($this->CONEXION);
        }
        /* retornara null o el id clave de la tabla consultada */
        return $resp;
    }
}
