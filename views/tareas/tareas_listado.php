<?php
    // Importar la clase Tareas desde el namespace Tareasing
    use Tareasing\Src\Tareas;

    // Inicia la sección del tablero
    $tableroHtml = '
        <section class="custom-container">
            <h3 class="text-center text-primary">
                Tablero de Tareas
            </h3>
            <div class="tareas-container">
    ';

    // Recorrer el array de datos de las tareas y generar instancias de la clase Tareas
    foreach ($datosTareas_user as $datosTarea) {
        // Crear una instancia de la clase Tareas para cada tarea
        $tareaObj = new Tareas (
            $datosTarea['rowid'],
            $datosTarea['idUser'],
            $datosTarea['name_user'],
            $datosTarea['idUser_cargo'],
            $datosTarea['name_user_cargo'],
            $datosTarea['nombre'],
            $datosTarea['descripcion'],
            $datosTarea['inicio'],
            $datosTarea['final'],
            $datosTarea['estadoID'],
            $datosTarea['estado']
        );

        // Usar el método __toString() para renderizar la tarjeta
        $tableroHtml .= $tareaObj;
    }

    // Cerrar el contenedor del tablero
    $tableroHtml .= '
            </div>
        </section>
    ';
    
    // Mostrar el tablero en pantalla
    echo $tableroHtml;