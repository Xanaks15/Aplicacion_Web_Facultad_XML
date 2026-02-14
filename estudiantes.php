<?php
$xml = simplexml_load_file("xmlgeneral.xml");
$materias= $xml->xpath("/facultad/posgrado/maestria/materias/materia");
if (isset($_GET["id"])) {
  #Recuperar datos el ID dado
  $estudiante = $xml->xpath("/facultad/posgrado/maestria/areas/area/alumnos/alumno[matricula='".$_GET["id"]."']");
  $Materias_Cursadas = array();
  foreach ($estudiante[0]->materias->materia as $materia) {
    array_push($Materias_Cursadas,strval($materia->attributes()));
  }
}
#echo $areas[0]["area"];
#print_r($areas);
#foreach ( $areas as $studen ) {
#  print($studen->matricula);
#}
#echo $areas[0]->matricula;
#echo $areas[0]->nombre;
 ?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Estudiante</title>
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
          console.log(response);
          if ( response == "0" ) {
            //Error Matrícula de alumno ya existe
              $( "<div>La matrícula ya ha sido establecida en otro alumno.</div>" ).dialog({
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
    function calcularEdad(fechaDeNacimiento) {
      var today = new Date();
      var birthDate = new Date(fechaDeNacimiento.replace(/-/g, "/"));
      var age = today.getFullYear() - birthDate.getFullYear();
      var m = today.getMonth() - birthDate.getMonth();
      if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
          age--;
      }
      $("#edad").val(age);
    }
  </script>
  <body class="fondo_main">
    <form role="form" id="formulario" name="formulario" action="javascript:guardar();">
    <br><br>
    <div class="container">
      <h2 align="center" class="titulo">Registrar Estudiante</h2>
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
                  <div class="form-group col-md-12">
                    <div class="input-group">
                      <span class="input-group-text">Área:</span>
                      <select class="custom-select" name="area">
                        <option value="BD" <?php if (isset($_GET["area"]) && $_GET["area"]=="BD") {echo "selected";}?>>Bases de Datos y Tratamiento de la Información</option>
                        <option value="SD" <?php if (isset($_GET["area"]) && $_GET["area"]=="SD") {echo "selected";}?>>Sistemas Distribuidos</option>
                        <option value="ISI" <?php if (isset($_GET["area"]) && $_GET["area"]=="ISI") {echo "selected";}?>>Ingeniería en Sistemas Inteligentes</option>
                        <option value="CM" <?php if (isset($_GET["area"]) && $_GET["area"]=="CM") {echo "selected";}?>>Computación Matemática</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label>Matrícula:</label>
                    <input type="number" name="matricula" class="form-control" required
                    <?php if (isset($_GET["id"])) {echo "value='".$estudiante[0]->matricula."' disabled";}?>>
                    <input type="hidden" name="id" id="id" value="<?php echo (isset($_GET["id"])?$_GET["id"]:"") ?>" />
                    <input type="hidden" name="acc" id="acc" value="<?php echo (isset($_GET["id"])?"2":"1") ?>" />
                    <input type="hidden" name="tipo" id="tipo" value="1" />
                  </div>
                  <div class="form-group col-md-8">
                    <label>Nombre completo:</label>
                    <input type="text" name="nombre" class="form-control" required
                    <?php if (isset($_GET["id"])) {echo "value='".$estudiante[0]->nombre."'";}?>>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label>CURP:</label>
                    <input type="text" name="curp" class="form-control" required
                    <?php if (isset($_GET["id"])) {echo "value='".$estudiante[0]->curp."'";}?>>
                  </div>
                  <div class="form-group col-md-4">
                    <label>RFC:</label>
                    <input type="text" name="rfc" class="form-control" required
                    <?php if (isset($_GET["id"])) {echo "value='".$estudiante[0]->rfc."'";}?>>
                  </div>
                  <div class="form-group col-md-4">
                    <label>Nº CVU:</label>
                    <input type="number" name="no_cvu" class="form-control" required
                    <?php if (isset($_GET["id"])) {echo "value='".$estudiante[0]->no_cvu."'";}?>>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label>Teléfono:</label>
                    <input type="telephone" name="telefono" class="form-control" required
                    <?php if (isset($_GET["id"])) {echo "value='".$estudiante[0]->telefono."'";}?>>
                  </div>
                  <div class="form-group col-md-8">
                    <label>Correo:</label>
                    <input type="text" name="correo" class="form-control" required
                    <?php if (isset($_GET["id"])) {echo "value='".$estudiante[0]->email."'";}?>>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-2">
                    <label>Promedio Actual:</label>
                    <input type="number" name="promedio" class="form-control" required
                    <?php if (isset($_GET["id"])) {echo "value='".$estudiante[0]->prom_actual."'";}?>>
                  </div>
                  <div class="form-group col-md-2">
                    <label>Créditos:</label>
                    <input type="number" name="creditos" class="form-control" required
                    <?php if (isset($_GET["id"])) {echo "value='".$estudiante[0]->creditos."'";}?>>
                  </div>
                  <div class="form-group col-md-8">
                    <label>Tutor:</label>
                    <input type="text" name="tutor" class="form-control" required
                    <?php if (isset($_GET["id"])) {echo "value='".$estudiante[0]->tutor."'";}?>>
                  </div>
                </div>
                <div class="form-row" style="margin-bottom:10px;">
                    <div class="form-group col-md-4">
                      <div class="input-group">
                        <span class="input-group-text">Género:</span>
                        <div class="custom-control custom-radio input-group-text" style="background:none; border:none; margin-left:5px;">
                          <input type="radio" id="radio1_1" name="genero" class="custom-control-input" value="M"
                          <?php if (isset($_GET["id"])) {if ($estudiante[0]->genero == 'M') {echo "checked";}}?> >
                          <label class="custom-control-label" for="radio1_1">Masculino</label>
                        </div>
                        <div class="custom-control custom-radio input-group-text" style="background:none; border:none; margin-left:5px;">
                          <input type="radio" id="radio1_2" name="genero" class="custom-control-input" value="F"
                          <?php if (isset($_GET["id"])) {if ($estudiante[0]->genero == 'F') {echo "checked";}}?> >
                          <label class="custom-control-label" for="radio1_2">Femenino</label>
                        </div>
                      </div>
                    </div>
                    <div class="form-group col-md-4">
                      <div class="input-group">
                        <span class="input-group-text">Fecha de nacimiento:</span>
                        <input type="text" class="form-control" placeholder="YYYY-MM-DD" id="fecha" name="fecha_nac" onchange="calcularEdad(this.value);" required
                        <?php if (isset($_GET["id"])) {echo "value='".$estudiante[0]->fecha_nac."'";}?>>
                      </div>
                    </div>
                    <div class="form-group col-md-4">
                      <div class="input-group">
                        <span class="input-group-text">Edad:</span>
                        <input type="text" class="form-control" id="edad" name="edad" readOnly="readOnly"
                        <?php if (isset($_GET["id"])) {echo "value='".$estudiante[0]->edad."'";} else {echo "value='0'";}?>>
                      </div>
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
                Grados Académicos
              </button>
            </h5>
          </div>
          <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
            <div class="card-body">
              <div class="container">
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label>Institución Educativa:</label>
                    <input type="text" name="escuela" class="form-control" required
                    <?php if (isset($_GET["id"])) {echo "value='".$estudiante[0]->grados_academicos->grado->escuela."'";}?>>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-8">
                    <label>Título obtenido:</label>
                    <input type="text" name="titulo" class="form-control" required
                    <?php if (isset($_GET["id"])) {echo "value='".$estudiante[0]->grados_academicos->grado->titulo."'";}?>>
                  </div>
                  <div class="form-group col-md-4">
                    <label>Promedio:</label>
                    <input type="number" name="promedio" class="form-control" step="0.01" required
                    <?php if (isset($_GET["id"])) {echo "value='".$estudiante[0]->grados_academicos->grado->promedio."'";}?>>
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
              <button type="button"class="btn btn-success" id="clonar_materia">Agregar Materia</button>
              <button type="button"class="btn btn-danger" id="eliminar_materia">Eliminar Materia</button>
            </h5>
          </div>
          <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
            <div class="card-body" id="padre">
              <div id="mat"><?php
                if (isset($Materias_Cursadas)&&(count($Materias_Cursadas)>0)) {
                  for ($i=0; $i < count($Materias_Cursadas); $i++) {
                    echo "<div class='form-row col-lg-12' id='mat".$i."'>";
                    echo "<div class='from-group col-lg-8'>
                      <select class='custom-select col-lg-12' name='materias[]'>
                        ";
                        foreach ($materias as $materia) {
                          $text="";
                          if (strval($materia->clave_mat)==$Materias_Cursadas[$i]) {
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
   $( "#fecha" ).datepicker({
     dateFormat: "yy-mm-dd",
     maxDate: "+0D",
     changeMonth: true,
     changeYear: true,
     yearRange: "-100:+0"
   });
   </script>
</html>
