<?php
/** Creación de la clase Login */

class Login 
{
    /**
     * @var object -Conexión a la base de datos
     */
    private $db_connection = null;
    /**
     * @var array -Array para mensajes de errores
     */
    public $errors = array();
    /**
     * @var array -Colección de procesos y mensajes
     */
    public $messages = array();

    /** La siguiente función "__construct()" se inicia de manera automática cada vez que se crea un objeto en la clase */

    public function __construct() {

        //Crear y leer la sesión de un usuario con la variable global "session_start()"
        session_start();

        //Condición en caso de que el usuario realice la acción de salida de su cuenta.
        if(isset($_GET["logout"])) {
            $this->doLogout();
        }
        
        //Logueo del usuario mediante post
        elseif(isset($_POST["login"])) {
            $this->dologinWithPostData();
        }
    }

    //Estructura funcional del login de un usuario del sistema
    private function dologinWithPostData() {
        //Verificación de los campos del formulario de acceso
        if(empty($_POST['user_name'])) {
            $this->errors[] = "Campo requerido, por favor ingrese el nombre de usuario";
        }elseif(empty($_POST['user_password'])) {
            $this->errors[] = "Campo requerido, por favor ingrese su contraseña";
        }elseif(!empty($_POST['user_name']) && !empty($_POST['user_password'])) {

            //Conexión a la base de datos con el uso de las constantes declaradas en "../models/database.php"
            $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            //Seteo de los caracteres en formato utf8
            if (!$this->db_connection->set_charset("utf8")) {
                $this->errors[] = $this->db_connection->error;
            }

            //Conexión a la base sin haber tenido problema con las conexiones de la misma
            if(!$this->db_connection->connect_errno) {
                $user_name = $this->db_connection->real_escape_string($_POST['user_name']);
                //Consulta a la base de datos
                $sql = "SELECT id_usuario, user_name, nombre, user_email, user_password_hash FROM usuarios WHERE user_name = '" . $user_name . "' OR user_email = '" . $user_name . "';";
                $result_login_check = $this->db_connection->query($sql);

                //Condición para verificar si el usuario existe
                if($result_login_check->num_rows == 1) {
                    //Obtención del objeto
                    $result_row = $result_login_check->fetch_object();

                    //Verificación de la contraseña del usuario
                    if (password_verify($_POST['user_password'], $result_row->user_password_hash)) {

                        // write user data into PHP SESSION (a file on your server)
                        $_SESSION['id_usuario'] = $result_row->id_usuario;
						$_SESSION['nombre'] = $result_row->nombre;
						$_SESSION['user_name'] = $result_row->user_name;
                        $_SESSION['user_email'] = $result_row->user_email;
                        $_SESSION['user_login_status'] = 1;

                    } else {
                        $this->errors[] = "La contraseña ingresada es incorrecta.";
                    }
                } else {
                    $this->errors[] = "El usuario o correo electrónico ingresado es incorrecto.";
                }
            } else {
                $this->errors[] = "Ha ocurrido un error con la base de datos!";
            }
        }
    }

    //Función para cerrar la sesión del usuario
    public function doLogout() {
        //Borrado de la sesión del usuario
        $_SESSION = array();
        session_destroy();
        //Mensaje de salida
        $this->messages[] = "La sesión ha sido cerrada exitosamente";
    }

    /**
     * @return boolean -- Estado del login de usuario
     */
    public function isUserLoggedIn(){
        if(isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] == 1 ){
            return true;
        }
        //Retorno por defecto
        return false;
    }
}