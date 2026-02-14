<?php
$xml = simplexml_load_file("xmlgeneral.xml");
$materias= $xml->xpath("/facultad/posgrado/maestria/materias/materia");
if (isset($_GET["id"])) {
  #Recuperar datos el ID dado
  $profesor = $xml->xpath("/facultad/posgrado/maestria/personal/profesores/profesor[@id_profesor=".$_GET["id"]."]");
  $Materias_impartidas = array();
  foreach ($profesor[0]->materias_imp->materia as $materia) {
    array_push($Materias_impartidas,strval($materia->attributes()));
  }
  #print_r($profesor);
} ?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Profesores</title>
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
          if ( response == "0" ) {
            //Error ID de profesor ya existe
              $( "<div>El ID ya ha sido establecido en otro profesor.</div>" ).dialog({
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
  <br><br>
    <h2 align="center" class="titulo">Registrar Profesor</h2>
    <form role="form" id="formulario" name="formulario" action="javascript:guardar();">
      <div class="container">
        <br>
        <div id="accordion">
          <div class="card">
            <div class="card-header" id="headingOne">
              <h5 class="mb-0">
                <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  Datos Personales
                </button>
              </h5>
            </div>

            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
              <div class="card-body">
                <div class="container">
                  <div class="form-row">
                    <div class="form-group col-md-4">
                      <label>ID Profesor:</label>
                      <input type="number" name="id_profesor" class="form-control" required
                      <?php if (isset($_GET["id"])) {echo "value='".$_GET["id"]."' disabled";}?>>
                      <input type="hidden" name="id" id="id" value="<?php echo (isset($_GET["id"])?$_GET["id"]:"") ?>" />
                      <input type="hidden" name="acc" id="acc" value="<?php echo (isset($_GET["id"])?"2":"1") ?>" />
                      <input type="hidden" name="tipo" id="tipo" value="2" />
                    </div>
                    <div class="form-group col-md-8">
                      <label>Nombre completo:</label>
                      <input type="text" name="nombre" class="form-control" required
                      <?php if (isset($_GET["id"])) {echo "value='".$profesor[0]->nombre."'";}?>>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-4">
                      <label>Cubículo:</label>
                      <input type="text" name="cubiculo" class="form-control" required
                      <?php if (isset($_GET["id"])) {echo "value='".$profesor[0]->ubicacion."'";}?>>
                    </div>
                    <div class="form-group col-md-8">
                      <label>Correo:</label>
                      <input type="text" name="correo" class="form-control" required
                      <?php if (isset($_GET["id"])) {echo "value='".$profesor[0]->correo_electronico."'";}?>>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header" id="headingTwo">
              <h5 class="mb-0">
                <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                  Publicaciones
                </button>
              </h5>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
              <div class="card-body">
                <div class="container">
                  <div class="form-row">
                    <div class="form-group col-md-12">
                      <label>Autores:</label>
                      <input type="text" name="autores" class="form-control"
                      <?php if (isset($_GET["id"])) {echo "value='".$profesor[0]->publicaciones->publicacion->autores."'";}?>>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-9">
                      <label>Título de la publicación:</label>
                      <input type="text" name="titulo_pub" class="form-control"
                      <?php if (isset($_GET["id"])) {echo "value='".$profesor[0]->publicaciones->publicacion->titulo."'";}?>>
                    </div>
                    <div class="form-group col-md-3">
                      <label>Año de publicación:</label>
                      <input type="number" name="anio" class="form-control"
                      <?php if (isset($_GET["id"])) {echo "value='".$profesor[0]->publicaciones->publicacion->anio."'";}?>>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header" id="headingThree">
              <h5 class="mb-0">
                <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                  Materias
                </button>
                <button type="button" class="btn btn-success" id="clonar_materia">Agregar Materia</button>
                <button type="button" class="btn btn-danger" id="eliminar_materia">Eliminar Materia</button>
              </h5>
            </div>
            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
              <div class="card-body" id="padre">
                <div id="mat"><?php
                  if (isset($Materias_impartidas)&&(count($Materias_impartidas)>0)) {
                    for ($i=0; $i < count($Materias_impartidas); $i++) {
                      echo "<div class='form-row col-lg-12' id='mat".$i."'>";
                      echo "<div class='from-group col-lg-8'>
                        <select class='custom-select col-lg-12' name='materias[]'>
                          ";
                          foreach ($materias as $materia) {
                            $text="";
                            if (strval($materia->clave_mat)==$Materias_impartidas[$i]) {
                              $text="Selected";
                            }
                            echo "<option $text value='".$materia->clave_mat."'> ".$materia->nombre."</option>";
                          }
                          echo "</select>
                            </div>
                          </div>";
                        }
                  }else{
                    echo "<div class='form-row col-lg-12' id='mat0'>";
                    echo "<div class='from-group col-lg-8'>
                      <select class='custom-select col-lg-12' name='materias[]'>
                        ";
                        foreach ($materias as $materia) {
                          echo "<option value='".$materia->clave_mat."'> ".$materia->nombre."</option>";
                        }
                        echo "</select>
                          </div>
                        </div>";
                  }
                  ?><div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <br>
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
    </form>
  </body>
  <script>
  $("#clonar_materia").on("click",function(){
    var cantidad= document.getElementsByName("materias[]").length;
    while ($("mat"+cantidad).length==1) {
      cantidad=cantidad+1;
    }
    $("#mat").append("<div class='form-row col-lg-12' id=mat"+cantidad+">"+document.getElementById("mat0").innerHTML+"</div>")
  })
  $("#eliminar_materia").on("click",function(){
    var cantidad= document.getElementsByName("materias[]").length;
    console.log(cantidad);
    if (cantidad!=1) {
      $("#mat"+(cantidad-1)).remove()
    }
  })
   </script>
</html>
