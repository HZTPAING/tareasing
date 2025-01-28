<section class="maine-container">
    <?php
        if (isset($_GET['views']) && !empty($_GET['views'])) {
            // TAREAS_LISTADO
            if ($_GET['views'] == 'tareas_listado') {
                if (!isset($_GET['action']) || empty($_GET['action'])) {
                    // Cargar pagina principal "/views/tareas/listado.php" de las tareas TRELLO
                    require_once(__DIR__ . '/tareas/tareas_listado.php');
                }
            }
        }
    ?>
</section>

<header class="container-fluid" style="height:150px;">
    <!-- div respuestas -->
    <div id="mensajeStatus" class="mt-3"></div>
    <div id="res">
        <?php
            if (isset($_GET['res']) && !empty($_GET['res'])) {
                echo urldecode($_GET['res']);
            }
        ?>
    </div>
</header>