<?php
namespace Tareasing\model;

use \Exception;

    Class ConectorBD {
        private $host;
        private $user;
        private $pass;
        private $db;
        private $port;
        public $con;

        public function __construct() {
            $this->host = HOST;
            $this->user = USER;
            $this->pass = PASS;
            $this->db = DB;
            $this->port = "3306";
            $this->ConectarBD();
        }

        public function ExecSQL($sql) {
            $this->ConectarBD();
            $datos = mysqli_query($this->con, $sql);
            $this->DesconectarBD();
            return $datos;
        }

        public function ConsultaSeguras($sql, $user) {
            $this->ConectarBD();
            // Preparar la consulta
            $stmt = $this->con->prepare($sql);
            // Verificar si la preparación de la consulta fue exitosa
            if ($stmt === false) {
                die("Error en la preparación de la consulta: ".$this->con->error);
            }
            // Vincular parámetros y valores
            $stmt->bind_param("s", $user);
            // Ejecutar la consulta
            $stmt->execute();
            // Almacenar el resultado
            $stmt->store_result();
            // Verificar si el usuario existe
            if ($stmt->num_rows > 0) {
                echo "El usuario existe con seguridad.";
            } else {
                echo "El usuario no existe con seguridad.";
            }
        }

        private function ConectarBD() {
            $this->con = mysqli_connect($this->host,$this->user,$this->pass,$this->db,$this->port);
        }

        private function DesconectarBD() {
            $res = mysqli_close($this->con);
            return $res;
        }
    }

    class UsuariosDB extends ConectorBD {
        public function CambiarEstadoUsuariosDB($user) {
            // Prevenir el SQL Inject
            $user = trim($user); // Eliminar espacios en blanco innecesarios
            $user = htmlspecialchars($user, ENT_QUOTES, 'UTF-8'); // Convertir caracteres especiales en entidades HTML
    
            // Consultar si el usuario existe sin seguridad
            $sql = "SELECT `rowid` FROM `app_users` WHERE `name` = '".$user."'";
            $datos = $this->ExecSQL($sql);
            if (mysqli_num_rows($datos) == 0) {
                return false;
            }
            // El usuario existe sin seguridad
            foreach ($datos as $dato);
            $sql = "UPDATE `app_users` SET `status` = '2' WHERE `rowid` = ".$dato['rowid'];
            $res = $this->ExecSQL($sql);
            if ($res != 1) {
                return false;
            }
            return true;
        }
    
        public function CambiarEstadoDB($id) {
            // Consultar el valor de estatus del usuario
            $sql = "SELECT `status` FROM `app_users` WHERE `rowid` = '".$id."'";
            $datos = $this->ExecSQL($sql);
            // Pasamos el resultado a un array
            foreach ($datos as $dato);
            // Si el estatus actual es 1, cambiamos a 2, y viceversa
            if ($dato['status'] == 2) {
                $sql = "UPDATE `app_users` SET `status` = '1' WHERE `rowid` = ".$id;
            } else {
                $sql = "UPDATE `app_users` SET `status` = '2' WHERE `rowid` = ".$id;
            }
            $res = $this->ExecSQL($sql);
            if ($res != 1) {
                return false;
            }
            return true;
        }
    
        public function GuardarUsuarioDB($datos) {
            if (isset($datos['status'])) {
                $status = $datos['status'];
            } else {
                $status = 1;
            }
    
            $sql = "INSERT INTO `app_users` 
            (`name`, `pass`, `email`, `status`)
            VALUES 
            ('". $datos['user']. "', '". MD5($datos['pass']). "', '". $datos['email']. "', ". $status .")
            ";
    
            $res_sql = $this->ExecSQL($sql);
            return $res_sql;
        }
    
        public function BuscarUsuarioDB() {
            $sql = "SELECT rowid, name, pass, email, status FROM `app_users` WHERE `status` = 2";
            $datos = $this->ExecSQL($sql);
            if (mysqli_num_rows($datos) == 0) {
                return false;
            }
            // El usuario existe sin seguridad
            $i = 0;
            foreach ($datos as $dato) {
                $datosUsers[$i] = $dato;
                $i = $i + 1;
            }
            return $datosUsers;
        }
    
        public function BuscarUsuarioDB_id($rowid) {
            $sql = "SELECT rowid, name, pass, email, status FROM `app_users` WHERE `rowid` = " .$rowid;
            try {
                $datos = $this->ExecSQL($sql);
                if (mysqli_num_rows($datos) == 0) {
                    return false;
                }
                // El usuario existe sin seguridad
                $i = 0;
                foreach ($datos as $dato);
                return $dato;
            } catch (Exception $e) {
                return $e;
            }
    
        }
    
        public function ListaUsuarioDB() {
            $sql = "SELECT rowid, name, pass, email, status FROM `app_users`";
            $datos = $this->ExecSQL($sql);
            if (mysqli_num_rows($datos) == 0) {
                return false;
            }
            // El usuario existe sin seguridad
            $i = 0;
            foreach ($datos as $dato) {
                $datosUsers[$i] = $dato;
                $i = $i + 1;
            }
            return $datosUsers;
        }
    
        public function UpdateUsuarioDB ($datos) {
            $sql = "UPDATE app_users SET 
                name = '". $datos['user']. "',
                email = '". $datos['email']. "',
                status = ". $datos['status']. "
                ";
            if (isset($datos['pass']) && !empty($datos['pass'])) {
                $sql.= ", pass = '". MD5($datos['pass']). "'";
            }
            $sql.= " WHERE rowid = ". $datos['rowid'];
            $res = $this->ExecSQL($sql);
            return $res;
        }
    
        public function BorrarUsuarioDB_id ($rowid) {
            $sql = "DELETE FROM app_users WHERE rowid = ". $rowid;
            $res = $this->ExecSQL($sql);
            return $res;
        }
    }
    
    class RolDB extends ConectorBD
    {
    
        public function CargarRoles()
        {
            $sql = "SELECT * FROM `app_rol`";
            $datos = $this->ExecSQL($sql);
            $total = mysqli_num_rows($datos);
            if ($total != 0) {
                $i = 1;
                foreach ($datos as $dato) {
                    $datos_rol[$i] = $dato;
                    $i++;
                }
                return $datos_rol;
            } else {
                return false;
            }
        }
        public function GuardarRol($datos)
        {
            $total = count($datos['dkeys']['key']);
            $sql = "";
            $sql .= "INSERT INTO `app_rol`";
            $sql .= '(`rowid`,`nombre`,';
            for ($i = 2; $i < $total; $i++) {
                if ($i != 17) {
                    $sql .= "`" . $datos['dkeys']['key'][$i] . "`,";
                } else {
                    $sql .= "`" . $datos['dkeys']['key'][$i] . "`";
                }
            }
            $sql .= ")VALUES(NULL,'" . $datos['nombre'] . "',";
            for ($i = 2; $i < $total; $i++) {
                if (!empty($datos['dkeys']['valor'][$i])) {
                    $valor = 1;
                } else {
                    $valor = 0;
                }
                if ($i != 17) {
    
                    $sql .= $valor . ",";
                } else {
                    $sql .= $valor;
                }
            }
            $sql .= ")";
            if ($this->ExecSQL($sql)) {
                return true;
            } else {
                return false;
            }
        }
        public function UpdateDatosRol(array $datos)
        {
    
            $sql = "UPDATE `app_rol` SET `{$datos['key']}`=" . $datos['valor'] . "  WHERE `rowid`=" . $datos['rowid'];
            if ($this->ExecSQL($sql)) {
                return true;
            } else {
                return false;
            }
        }
        public function ConsultarValorActual(int $rowid, string $key)
        {
            $sql = "SELECT `{$key}` FROM `app_rol` WHERE `rowid`=" . $rowid;
            $datos = $this->ExecSQL($sql);;
            foreach ($datos as $dato);
            return $dato[$key];
        }
        public function DeleteRol(int $rowid)
        {
            $sql = "DELETE FROM `app_rol` WHERE `rowid`=" . $rowid;
            if ($this->ExecSQL($sql)) {
                return true;
            } else {
                return false;
            }
        }
    
        public function RelacionUserRol($iduser)
        {
            $sql = "SELECT * FROM `app_users_rol` WHERE `iduser`=" . $iduser;
            $datos = $this->ExecSQL($sql);
            $total = mysqli_num_rows($datos);
            if ($total != 0) {
                $i = 1;
                foreach ($datos as $dato) {
                    $datos_rol[$i] = $dato;
                    $i++;
                }
                return $datos_rol;
            } else {
                return false;
            }
        }
        public function GuardarRolDB(array $datos)
        {
            if (!empty($datos['rowid'])) {
                echo $sql = "UPDATE `app_users_rol` SET `iduser`=" . $datos['iduser'] . ",`idrol`=" . $datos['idrol'] . " WHERE `rowid` = '" . addslashes($datos['rowid']) . "'";
            } else {
                echo $sql = "INSERT INTO `app_users_rol` (`iduser`,`idrol`) VALUES (" . $datos['iduser'] . "," . $datos['idrol'] . ")";
            }
    
    
            $res = $this->ExecSQL($sql);
            return ($res !== false);
        }
        public function ConsultarRolDB($iduser)
        {
            $sql = "SELECT a.idrol, b.nombre FROM app_users_rol a, app_rol b WHERE a.idrol = b.rowid AND a.iduser = " . $iduser;
            $datos = $this->ExecSQL($sql);
            $total = mysqli_num_rows($datos);
            if ($total > 0) {
                foreach ($datos as $dato);
                return $dato;
            } else {
                return false;
            }
        }
    }
    
    class TareasDB extends ConectorBD {
        public function InstallTareas() {
            $sql = "CREATE TABLE IF NOT EXISTS app_tareas (
                    rowid INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                    idUser INT NOT NULL,
                    idUser_cargo INT NOT NULL,
                    nombre VARCHAR(75),
                    descripcion VARCHAR(245),
                    inicio DATE,
                    final DATE,
                    estadoID INT,
                    estado ENUM('activa', 'pendiente', 'finalizada', 'en curso', 'cancelada', 'fallada')
                );
                ALTER TABLE app_tareas 
                ADD CONSTRAINT appUsers_appTareas
                FOREIGN KEY (idUser) REFERENCES app_users(rowid)
                ON UPDATE CASCADE ON DELETE CASCADE;
                ";
            $res = $this->ExecSQL($sql);
            return $res;
    
        }
    
        public function GuardarTareaDB($datos) {
            $sql = "INSERT INTO `app_tareas`
                (`idUser`, `idUser_cargo`, `nombre`, `descripcion`,
                `inicio`, `final`,  `estadoID`, `estado`)
                VALUES
                (
                " . $datos['idUser'] . ",
                " . $datos['idUser_cargo'] . ",
                '" . $datos['nombre'] . "',
                '" . $datos['descripcion'] . "',
                '" . $datos['fechaInicio'] . "',
                '" . $datos['fechaFinal'] . "',
                0,
                '" . $datos['estado'] . "'
                )";
            $res_sql = $this->ExecSQL($sql);
            return $res_sql;
        }
    
        public function ListaTareasDB_usuario($idUser) {
            $sql = "SELECT 
                a.`rowid`,
                `idUser`,
                b.name AS name_user,
                `idUser_cargo`,
                c.name AS name_user_cargo,
                `nombre`,
                `descripcion`,
                `inicio`,
                `final`,
                `estadoID`,
                `estado`
            FROM `app_tareas` a
                JOIN app_users b ON b.rowid = a.idUser
                JOIN app_users c ON c.rowid = a.idUser_cargo
                WHERE idUser = " . $idUser . " 
            ORDER BY a.rowid";
            $datos = $this->ExecSQL($sql);
            if (mysqli_num_rows($datos) == 0) {
                return [];
            }
            // Las tareas del usuario existen
            $datosTareas_user = []; // Inicializar la variable
            foreach ($datos as $dato) {
                $datosTareas_user[] = $dato;
            }
            return $datosTareas_user;
        }
    
        public function BuscarTareaDB_id($idTarea) {
            $sql = "SELECT
                `rowid`,
                `idUser`,
                `idUser_cargo`,
                `nombre`,
                `descripcion`,
                `inicio`,
                `final`,
                `estadoID`,
                `estado`
            FROM `app_tareas`
                WHERE rowid = " . $idTarea;
            try {
                $datos = $this->ExecSQL($sql);
                if (mysqli_num_rows($datos) == 0) {
                    return [];
                }
                // La tarea existe
                foreach ($datos as $dato);
                return $dato;
            } catch (\Exception $e) {
                return $e;
            }
        }
    
        public function UpdateTareaDB($datos) {
            $sql = "UPDATE app_tareas SET
                idUser_cargo = '". $datos['idUser_cargo']. "',
                nombre = '". $datos['nombre']. "',
                descripcion = '". $datos['descripcion']. "',
                inicio = '". $datos['fechaInicio']. "',
                final = '". $datos['fechaFinal']. "',
                estadoID = ". $datos['estadoID']. ",
                estado = '". $datos['estado']. "'
                WHERE rowid = ". $datos['rowid'];
            $res = $this->ExecSQL($sql);
            return $res;
        }
    
        public function BorrarTareaDB_id($idTarea) {
            $sql = "DELETE FROM app_tareas WHERE rowid = ". $idTarea;
            $res = $this->ExecSQL($sql);
            return $res;
        }
    }
    