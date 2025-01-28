<?php
    use Tareasing\model\RolDB;
    use Tareasing\model\UsuariosDB;

    // Cargar la clase saludo para el saludo personalizado
    use Saludo\Saludo\Saludo;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

     // Carga config.php
    require_once(__DIR__ . '/../config.php');
    
    // Carga automáticamente todas las dependencias de Composer (incluyendo PHPMailer) utilizando el autoloader generado
    require_once(__DIR__ . '/../vendor/autoload.php');
    
    // Carga el model.php para trabajar con la base de datos
    require_once(__DIR__ . '/../model/model.php');
    require_once(__DIR__ . '/../model/model_db.php');

    // Carga el controlador de las tareas "tareas_controller.php"
    require_once(__DIR__. '/tareas_controller.php');

    $roldb = new RolDB;

    $Saludo = new Saludo(isset($_SESSION['name']) ? $_SESSION['name'] : 'Invitado');
    
    if ($_GET) {
        if (isset($_GET['action']) &&!empty($_GET['action'])) {

        }
    }

    if ($_POST) {
        if (isset($_POST['action']) and !empty($_POST['action'])) {
            // FORM_LOGIN
            if ($_POST['action'] == 'FORM_LOGIN') {
                // Logear un usuario
                $datos['email'] = trim(htmlspecialchars_decode($_POST['user']));
                $datos['pass'] = trim(htmlspecialchars_decode($_POST['pass']));
                echo '<pre>';
                    print_r($datos);
                echo '</pre>';
                // buscamos datos en la BD
                $db = new UsuariosDB();
                $datos_bd = $db->BuscarUsuarioDB($datos);
                $userExiste = 0;
                foreach($datos_bd as $datos_user) {
                    // Verificar si el usuario y la contraseña se compare con uno de los usuarios existentes
                    if ($datos_user['email'] == $datos['email'] && $datos_user['pass'] == MD5($datos['pass'])) {
                        $userExiste = 1;
                        session_start();
                        $_SESSION['email'] = $datos_user['email'];
                        $_SESSION['name'] = $datos_user['name'];
                        $_SESSION['rowid'] = $datos_user['rowid'];

                        $rol = $roldb->ConsultarRolDB($datos_user['rowid']);
                        $_SESSION['rol'] = $rol['idrol'];
                        $_SESSION['rol_nombre'] = $rol['nombre'];
                    }
                }
                // Verificamos el resultado de comparacion de useario y contraseña
                if ($userExiste == 1) {
                    // Lanzar en pantalla que el usuario se puede logear
                    $res = '<div class="alert alert-success">El usuario <strong>'.$_SESSION['email'].'</strong> ya ha logeado con exito</div>';
                    header('location:' . BASE_URL . '/index.php?res=' . urlencode($res) . '&views=tareas_listado');
                } else {   
                    $res = '<div class="alert alert-danger">El usuario <strong>'.$datos['email'].'</strong> NO ha logeado con exito<br>La contraeña o el correo no es correto</div>';
                    header('location:' . BASE_URL . '/index.php?res=' . urlencode($res));
                }
            }
            // CERRAR
            if (isset($_GET['action']) &&!empty($_GET['action'])) {
                if ($_GET['action'] == 'cerrar') {
                    // Iniciar la sesión si no está activa
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    
                    // Eliminar todas las variables de sesión
                    $_SESSION = [];

                    // Destruir la sesión
                    session_destroy();

                    // Eliminar la cookie de sesión (si existe)
                    if (ini_get("session.use_cookies")) {
                        $params = session_get_cookie_params();
                        setcookie(session_name(), '', time() - 3600, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
                    }

                    // Redirigir al usuario a la página de inicio de sesión
                    //header('Location: ' . BASE_URL . '/index.php?views=login');
                    //exit(); // Asegurarse de que no se ejecute más código
                }
            }
        }
    }

?>