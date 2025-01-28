<?php
    // use \Exception;
    use Tareasing\model\FiltrarDatos;
    use Tareasing\model\TareasDB;
    use Tareasing\model\DatosDB;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    /*
    require_once(__DIR__ . '/../model/model.php');
    require_once(__DIR__ . '/../model/model_db.php');
    */

    if ($_GET) {
        // limpiar los dados en la variabla GET y validarla
        $filtro = new FiltrarDatos();
        $datos_get = $filtro->Filtrar($_GET);
        if (isset($datos_get['views']) && !empty($datos_get['views'])) {
            if ($datos_get['views'] == 'tareas_listado') {
                // Mostrar la lista de las tareas
                if (isset($_SESSION['rowid']) && !empty($_SESSION['rowid'])) {
                    if (!isset($_GET['action']) || empty($_GET['action'])) {
                        // Consultamos las tareas del usuario de BD
                        $tareasDB = new TareasDB();
                        $datosTareas_user = $tareasDB->ListaTareasDB_usuario($_SESSION['rowid']);
                    } else {

                    }
                }
            }
        }
    }

    if ($_POST) {
        // limpiar los dados en la variabla GET y validarla
        try {
            $filtro = new FiltrarDatos();
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'msg01' => 'Error al intentar filtrar los datos',
                'msg02' => '',
                'msg03' => $e->getMessage()
            ]);
            exit();
        }

        $datos_post = $filtro->Filtrar($_POST);

        if (isset($datos_post['views']) && !empty($datos_post['views'])) {
            if ($datos_post['views'] == 'tareas_listado') {
                // Borrar la tarea
                if (isset($datos_post['action']) && $datos_post['action'] == 'DELETE_TAREA_AJAX') {
                    // Borramos la tarea
                    try {
                        // $datosDB_tareas = new DatosDB('app_tareas');
                        $datosDB_tareas = new TareasDB();
                        // $result = $datosDB_tareas->DeleteDatosId('rowid', $datos_post['rowid']);
                        $result = $datosDB_tareas->BorrarTareaDB_id($datos_post['rowid']);
                    } catch (Exception $e) {
                        echo json_encode([
                            'success' => false,
                            'msg01' => 'Error al conectar con la base de datos',
                            'msg02' => '',
                            'msg03' => $e->getMessage()
                        ]);
                        exit();
                    }

                    if ($result) {
                        // Si la eliminación es exitosa, devolvemos una respuesta JSON
                        echo json_encode([
                            'success' => true,
                            'msg01' => 'La tarea ',
                            'msg02' => $datos_post['nombre'],
                            'msg03' => 'se ha borrado correctamente'
                        ]);
                    } else {
                        // Si ocurrió un error al intentar eliminar
                        echo json_encode([
                            'success' => false,
                            'msg01' => 'Error al intentar eliminar la tarea ',
                            'msg02' => $datos_post['nombre'],
                            'msg03' => '!'
                        ]);
                    }
                    exit();
                }
            }
        }
    }
