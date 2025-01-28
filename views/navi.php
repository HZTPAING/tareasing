<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent_maine">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            </ul>
            <div class="d-flex align-items-center">
                <!-- REQUISITOS ... -->
                <div class="navbar-text me-3">
                    <?php
                        $Saludo->getSaludo();
                    ?>
                </div>
                <!-- BOTON CERRAR LA SESION Y VOLVER A LA PAGINA LOGIN -->
                <a class="btn btn-danger" href="<?= BASE_URL . '/index.php?action=cerrar&views=login' ?>" style="margin-left:1rem;">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </a>
            </div>
        </div>
    </div>
    <div>
        <?php

        ?>
    </div>
</nav>