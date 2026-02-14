<?php
//session_start(['cookie_lifetime' => 3600,]);
$enlace_actual = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
?>
<div class="card">
  <div class="card-body" style="height:7rem;">
    <div class="container-fluid">
      <div class="row aling-items-center">
        <div class="col align-self-center">
          <!-- Parte Izquierda del navbar -->
          <img src="assets\ubisalud.png" alt="" width="350px">
        </div>
        <div class="col align-self-center">
          <!-- Parte central del navbar -->
          <?php if (stripos ($enlace_actual,"menuprincipal")): ?>
            <button type="button" id="nuevo" class="btn btn-success align-self-center">
              Agregar nuevo elemento
            </button>
          <?php endif; ?>
          <?php if (stripos ($enlace_actual,"quienesomos")): ?>
            <!--<h1>¿Quiénes Somos?</h1>-->
          <?php endif; ?>
        </div>
          <div class="col align-self-center" style="text-align: right;">
          <!-- Parte derecha del navbar -->
          <?php if (isset($_SESSION["validado"])): ?>
            <button type="button" id="menu" class="btn btn-success align-self-center" style="display: none;">
              Menu principal
            </button>
            <button type="button" id="edicion" class="btn btn-primary align-self-center">
              Alimentos registrados
            </button>
            <button type="button" id="salir" class="btn btn-danger align-self-center">
              Cerrar sesión
            </button>
          <?php else: ?>
            <button type="button" id="login" class="btn btn-primary align-self-center">
              Iniciar sesión
            </button>
            <button type="button" class="btn btn-primary align-self-center" id="quien" onclick="document.location='quienesomos.php';">¿Quiénes Somos?</button>
          <?php endif; ?>
          <?php if (stripos ($enlace_actual,"quienesomos")): ?>
            <button type="button" class="btn btn-success align-self-center" id="menu">Regresar</button>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>
</div>
