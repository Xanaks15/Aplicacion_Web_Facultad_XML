<?php
if (isset($_GET["id"])) {
  #Recuperar datos el ID dado
  $xml = simplexml_load_file("xmlgeneral.xml");
  $materia = $xml->xpath("/facultad/posgrado/maestria/materias/materia[clave_mat=".$_GET["id"]."]");
  //print_r($materia);
} ?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Materias</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"/>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/jquery-ui.min.css"/>
    <link rel="stylesheet" href="css/estilos.css"/>
    <script src="js/external/jquery/jquery.js"></script>
    <script src="js/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"/>
  </head>
  <script type="text/javascript">
    function guardar() {
      $.ajax({
        //Guardar/Editar Registro
        url: "include/funciones.php",
        type: "post",
        data: $("#formulario").serialize(),
        success: function (response) {
          //console.log(response);
          if ( response == "0" ) {
            //Error clave de materia ya existe
              $( "<div>La clave ya ha sido establecida en otra materia.</div>" ).dialog({
                title:"Error",
                resizable: false,
                height: "auto",
                width: 400,
                modal: true,
                buttons: {
                  "Entendido": function() {
                    $( this ).dialog( "close" );
                  }
                }
              });
          } else {
            $( "<div>Accion Completada.</div>" ).dialog({
              title:"Acción Completada",
              resizable: false,
              height: "auto",
              width: 400,
              modal: true,
              buttons: {
                "Entendido": function() {
                  $( this ).dialog( "close" );
                  document.location='xmlgeneral.xml';
                }
              }
            });
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
        }
  		});
    }
  </script>
  <body class="fondo_main">
    <br/><br/>
    <h2 align="center" class="titulo">Registrar Materia</h2>
      <form role="form" id="formulario" name="formulario" action="javascript:guardar();">
        <div class="container" style="margin-top:50px;">
          <div class="card">
            <h5 class="card-header d-flex justify-content-center">Datos de la materia</h5>
            <div class="card-body">
              <div class="container" style="width:80rem; margin-top:20px; margin-bottom:20px;">
                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label>Clave materia:</label>
                    <input type="number" name="clave_mat" id="clave_mat" class="form-control" required
                    <?php if (isset($_GET["id"])) {echo "value='".$materia[0]->clave_mat."' disabled";}?>>
                    <input type="hidden" name="id" id="id" value="<?php echo (isset($_GET["id"])?$_GET["id"]:"") ?>" />
                    <input type="hidden" name="acc" id="acc" value="<?php echo (isset($_GET["id"])?"2":"1") ?>" />
                    <input type="hidden" name="tipo" id="tipo" value="3" />
                  </div>
                  <div class="form-group col-md-7">
                    <label>Nombre de la materia:</label>
                    <input type="text" name="nombre" class="form-control" required
                    <?php if (isset($_GET["id"])) {echo "value='".$materia[0]->nombre."'";}?>>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label>Salón:</label>
                    <input type="number" name="salon" class="form-control" required
                    <?php if (isset($_GET["id"])) {echo "value='".$materia[0]->salon."'";}?>>
                  </div>
                  <div class="form-group col-md-7">
                    <label>Horario:</label>
                    <input type="text" name="horario" class="form-control" required
                    <?php if (isset($_GET["id"])) {echo "value='".$materia[0]->horario."'";}?>>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label>Créditos:</label>
                    <input type="number" name="creditos" class="form-control" required
                    <?php if (isset($_GET["id"])) {echo "value='".$materia[0]->creditos."'";}?>>
                  </div>
                  <div class="form-group col-md-7">
                    <label>Periodo:</label>
                    <input type="text" name="periodo" class="form-control" required
                    <?php if (isset($_GET["id"])) {echo "value='".$materia[0]->periodo."'";}?>>
                  </div>
                </div>
                <?php if (!isset($_GET["id"])): ?>
                  <div align="center" style="margin-bottom: 20px;">
                    <button type="submit" class="btn btn-primary boton_guardar" style="width:350px;">Guardar</button>
                  </div>
                <?php else: ?>
                  <div align="center" style="margin-bottom: 20px;">
                    <button type="submit" class="btn btn-primary boton_editar" style="width:350px;">Editar</button>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </form>
  </body>
</html>
