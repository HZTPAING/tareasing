<?php
    namespace Tareasing\src;

    class Tareas {
        // Atributos de la tarea
        private $rowid;             // 12
        private $idUser;            // 58
        private $name_user;         // HZTPASERG
        private $idUser_cargo;      // 59
        private $name_user_cargo;   // GRIASERHII
        private $nombre;            // TAREA_01
        private $descripcion;       // Descripcion de la TAREA_01
        private $inicio;            // 2025-01-24
        private $final;             // 2025-01-31
        private $estadoID;          // 0
        private $estado;            // finalizada

        // Constructor
        public function __construct($rowid, $idUser, $name_user, $idUser_cargo, $name_user_cargo, $nombre, $descripcion, $inicio, $final, $estadoID, $estado) {
            $this->rowid = $rowid;
            $this->idUser = $idUser;
            $this->name_user = $name_user;
            $this->idUser_cargo = $idUser_cargo;
            $this->name_user_cargo = $name_user_cargo;
            $this->nombre = $nombre;
            $this->descripcion = $descripcion;
            $this->inicio = $inicio;
            $this->final = $final;
            $this->estadoID = $estadoID;
            $this->estado = $estado;
        }

        public function __toString()
        {
            // Clase CSS para el color del estado
            $estadoColorClass = $this->getEstadoColorClass($this->estado);

            // Generar la card de la tarea
            $cardHtml = '
                <div class="card tarea-card ' . $estadoColorClass . ' m-2" style="width: 18rem;">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span class="fw-bold">' . htmlspecialchars($this->nombre) . '</span>
                        <span class="badge ' . $this->getBadgeColorClass($this->estado) . '">' . htmlspecialchars($this->estado) . '</span>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><strong>Descripción:</strong> ' . htmlspecialchars($this->descripcion) . '</p>
                        <p class="card-text"><strong>Usuario Asignado:</strong> ' . htmlspecialchars($this->name_user_cargo) . '</p>
                        <p class="card-text"><strong>Fecha Inicio:</strong> ' . htmlspecialchars($this->inicio) . '</p>
                        <p class="card-text"><strong>Fecha Final:</strong> ' . htmlspecialchars($this->final) . '</p>
                    </div>
                    <div class="card-footer text-end">
                        <button class="btn btn-primary btn-sm" 
                            data-rowid = ' . $this->rowid . '
                            data-nombre = ' . $this->nombre . '
                            >Editar
                        </button>
                        <button class="btn btn-danger btn-sm"
                            onclick="eliminarTarea(event)"
                            data-rowid = ' . $this->rowid . '
                            data-nombre = ' . $this->nombre . '
                            >Eliminar
                        </button>
                    </div>
                </div>
            ';
            
            return $cardHtml;
        }

        // Método para determinar la clase CSS del estado
        private function getEstadoColorClass($estado) {
            switch (strtolower($estado)) {
                case "activa":
                    return "card-activa";
                case "pendiente":
                    return "card-pendiente";
                case "finalizada":
                    return "card-finalizada";
                case "en_marcha":
                    return "card-en-marcha";
                case "cancelada":
                    return "card-cancelada";
                case "fallada":
                    return "card-fallada";
                default:
                    return "card-default";
            }
        }

        // Método para determinar la clase CSS del badge según el estado
        private function getBadgeColorClass($estado) {
            switch (strtolower($estado)) {
                case "activa":
                    return "bg-primary"; // Azul
                case "pendiente":
                    return "bg-warning text-dark"; // Amarillo
                case "finalizada":
                    return "bg-success"; // Verde
                case "en_marcha":
                    return "bg-orange"; // Naranja (debes definir esta clase en CSS)
                case "cancelada":
                    return "bg-danger"; // Rojo
                case "fallada":
                    return "bg-purple text-light"; // Púrpura (debes definir esta clase en CSS)
                default:
                    return "bg-secondary"; // Gris (valor por defecto)
            }
        }
    }