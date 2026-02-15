<?php
$xml = simplexml_load_file("xmlgeneral.xml");

if (isset($_GET["id"])) {
  #Recuperar datos del ID dado (Número de Inventario)
  $equipo = $xml->xpath("/facultad/posgrado/maestria/inventario/equipo[no_inventario='" . $_GET["id"] . "']");
}
?>
<!DOCTYPE html>
<html lang="es" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>Inventario de Hardware</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" />
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="css/jquery-ui.min.css" />
  <link rel="stylesheet" href="css/estilos.css" />
  <script src="js/external/jquery/jquery.js"></script>
  <script src="js/jquery-ui.js"></script>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" />
</head>

<script type="text/javascript">
  function guardar() {

    // Validación extra en front (no reemplaza la validación en PHP)
    var costo = parseFloat($("input[name='costo']").val());
    if (isNaN(costo) || costo <= 0) {
      $("<div>El costo debe ser mayor a 0.</div>").dialog({
        title: "Error de Validación",
        draggable: false,
        position: { my: "center", at: "center", of: window },
        resizable: false,
        height: "auto",
        width: 400,
        modal: true,
        buttons: {
          "Entendido": function () {
            $(this).dialog("close");
          }
        }
      });
      return; // CANCELA envío
    }

    $.ajax({
      url: "include/funciones.php",
      type: "post",
      data: $("#formulario").serialize(),
      success: function (response) {

        response = $.trim(response);
        console.log("RESPUESTA:", response);

        // --- Responsable no existe ---
        if (response === "RESPONSABLE_NO_EXISTE") {
          $("<div>El ID del Responsable no existe en Profesores ni Alumnos.</div>").dialog({
            title: "Error de Validación",
            draggable: false,
            position: { my: "center", at: "center", of: window },
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
              "Entendido": function () {
                $(this).dialog("close");
              }
            }
          });
          return;
        }

        // --- Inventario duplicado (nuevo comportamiento) ---
        // Por compatibilidad: si el backend aún devolviera "0", también lo tratamos como duplicado.
        if (response === "INVENTARIO_DUPLICADO" || response === "0") {
          $("<div>El Número de Inventario ya existe. Debe ser único.</div>").dialog({
            title: "Error de Validación",
            draggable: false,
            position: { my: "center", at: "center", of: window },
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
              "Entendido": function () {
                $(this).dialog("close");
              }
            }
          });
          return;
        }

        // --- Costo inválido ---
        if (response === "COSTO_INVALIDO") {
          $("<div>El costo debe ser mayor a 0.</div>").dialog({
            title: "Error de Validación",
            draggable: false,
            position: { my: "center", at: "center", of: window },
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
              "Entendido": function () {
                $(this).dialog("close");
              }
            }
          });
          return;
        }

        // --- Error al guardar XML ---
        if (response === "ERROR_GUARDAR_XML") {
          $("<div>Ocurrió un error al guardar el XML.</div>").dialog({
            title: "Error",
            draggable: false,
            position: { my: "center", at: "center", of: window },
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
              "Entendido": function () {
                $(this).dialog("close");
              }
            }
          });
          return;
        }

        // --- Éxito ---
        if (response === "1") {
          $("<div>Acción Completada.</div>").dialog({
            title: "Acción Completada",
            draggable: false,
            position: { my: "center", at: "center", of: window },
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
              "Entendido": function () {
                $(this).dialog("close");
                document.location = 'xmlgeneral.xml';
              }
            }
          });
          return;
        }

        // --- Si llega algo inesperado, NO asumir éxito ---
        $("<div>Respuesta inesperada del servidor:<br><br><code>" + $("<div/>").text(response).html() + "</code></div>").dialog({
          title: "Aviso",
          draggable: false,
          position: { my: "center", at: "center", of: window },
          resizable: false,
          height: "auto",
          width: 500,
          modal: true,
          buttons: {
            "Entendido": function () {
              $(this).dialog("close");
            }
          }
        });
      },
      error: function (xhr, ajaxOptions, thrownError) {
        alert(xhr.status);
      }
    });
  }
</script>

<body class="fondo_main">
  <form role="form" id="formulario" name="formulario" action="javascript:guardar();">
    <br><br>
    <div class="container">
      <h2 align="center" class="titulo"><?php echo (isset($_GET["id"]) ? "Editar Equipo" : "Registrar Equipo"); ?></h2>
      <br>
      <input type="hidden" name="acc" id="acc" value="<?php echo (isset($_GET["id"]) ? "2" : "1") ?>" />
      <input type="hidden" name="tipo" id="tipo" value="4" />

      <div id="accordion">
        <div class="card">
          <div class="card-header" id="headingOne">
            <h5 class="mb-0">
              <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"
                aria-expanded="true" aria-controls="collapseOne">
                Datos del Equipo
              </button>
            </h5>
          </div>

          <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">
              <div class="container">

                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label>Número de Inventario / Etiqueta BUAP:</label>
                    <input type="text" name="no_inventario" class="form-control" required
                      oninvalid="this.setCustomValidity('Por favor, complete este campo.')"
                      oninput="this.setCustomValidity('')" <?php if (isset($_GET["id"])) {
                        echo "value='" . $equipo[0]->no_inventario . "' readonly";
                      } ?>>
                    <?php if (isset($_GET["id"])) { ?>
                      <input type="hidden" name="id_original" value="<?php echo $_GET["id"]; ?>">
                    <?php } ?>
                  </div>
                  <div class="form-group col-md-6">
                    <label>Número de Serie:</label>
                    <input type="text" name="serie" class="form-control" required
                      oninvalid="this.setCustomValidity('Por favor, complete este campo.')"
                      oninput="this.setCustomValidity('')" <?php if (isset($_GET["id"])) {
                        echo "value='" . $equipo[0]->serie . "'";
                      } ?>>
                  </div>
                </div>

                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label>Tipo de Equipo:</label>
                    <select class="custom-select" name="tipo_equipo" required
                      oninvalid="this.setCustomValidity('Por favor, seleccione una opción.')"
                      oninput="this.setCustomValidity('')">
                      <option value="">Seleccione...</option>
                      <option value="Laptop" <?php if (isset($_GET["id"]) && $equipo[0]->tipo == 'Laptop')
                        echo "selected"; ?>>Laptop</option>
                      <option value="Desktop" <?php if (isset($_GET["id"]) && $equipo[0]->tipo == 'Desktop')
                        echo "selected"; ?>>Desktop</option>
                      <option value="Cañón" <?php if (isset($_GET["id"]) && $equipo[0]->tipo == 'Cañón')
                        echo "selected"; ?>>Cañón</option>
                      <option value="Server" <?php if (isset($_GET["id"]) && $equipo[0]->tipo == 'Server')
                        echo "selected"; ?>>Server</option>
                      <option value="Tablet" <?php if (isset($_GET["id"]) && $equipo[0]->tipo == 'Tablet')
                        echo "selected"; ?>>Tablet</option>
                      <option value="Switch" <?php if (isset($_GET["id"]) && $equipo[0]->tipo == 'Switch')
                        echo "selected"; ?>>Switch</option>
                    </select>
                  </div>
                  <div class="form-group col-md-4">
                    <label>Marca:</label>
                    <input type="text" name="marca" class="form-control" required
                      oninvalid="this.setCustomValidity('Por favor, complete este campo.')"
                      oninput="this.setCustomValidity('')" <?php if (isset($_GET["id"])) {
                        echo "value='" . $equipo[0]->marca . "'";
                      } ?>>
                  </div>
                  <div class="form-group col-md-4">
                    <label>Modelo:</label>
                    <input type="text" name="modelo" class="form-control" required
                      oninvalid="this.setCustomValidity('Por favor, complete este campo.')"
                      oninput="this.setCustomValidity('')" <?php if (isset($_GET["id"])) {
                        echo "value='" . $equipo[0]->modelo . "'";
                      } ?>>
                  </div>
                </div>

                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label>Procesador / Specs:</label>
                    <input type="text" name="procesador" class="form-control" placeholder="Ej. Intel i7, 16GB RAM"
                      required oninvalid="this.setCustomValidity('Por favor, complete este campo.')"
                      oninput="this.setCustomValidity('')" <?php if (isset($_GET["id"])) {
                        echo "value='" . $equipo[0]->procesador . "'";
                      } ?>>
                  </div>
                </div>

                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label>Fecha de Adquisición:</label>
                    <input type="text" class="form-control" id="fecha_adquisicion" name="fecha_adquisicion"
                      placeholder="YYYY-MM-DD" required
                      oninvalid="this.setCustomValidity('Por favor, complete este campo.')"
                      oninput="this.setCustomValidity('')" <?php if (isset($_GET["id"])) {
                        echo "value='" . $equipo[0]->fecha_adquisicion . "'";
                      } ?>>
                  </div>
                  <div class="form-group col-md-4">
                    <label>Costo de Compra:</label>
                    <input type="number" step="0.01" min="0.01" name="costo" class="form-control" required
                      oninvalid="this.setCustomValidity('El costo debe ser mayor a 0.')"
                      oninput="this.setCustomValidity('')" <?php if (isset($_GET["id"])) {
                        echo "value='" . $equipo[0]->costo . "'";
                      } ?>>
                  </div>
                  <div class="form-group col-md-4">
                    <label>Estado Físico:</label>
                    <select class="custom-select" name="estado" required
                      oninvalid="this.setCustomValidity('Por favor, seleccione una opción.')"
                      oninput="this.setCustomValidity('')">
                      <option value="">Seleccione...</option>
                      <option value="Nuevo" <?php if (isset($_GET["id"]) && $equipo[0]->estado == 'Nuevo')
                        echo "selected"; ?>>Nuevo</option>
                      <option value="Bueno" <?php if (isset($_GET["id"]) && $equipo[0]->estado == 'Bueno')
                        echo "selected"; ?>>Bueno</option>
                      <option value="Regular" <?php if (isset($_GET["id"]) && $equipo[0]->estado == 'Regular')
                        echo "selected"; ?>>Regular</option>
                      <option value="Malo" <?php if (isset($_GET["id"]) && $equipo[0]->estado == 'Malo')
                        echo "selected"; ?>>Malo</option>
                      <option value="Para Baja" <?php if (isset($_GET["id"]) && $equipo[0]->estado == 'Para Baja')
                        echo "selected"; ?>>Para Baja</option>
                    </select>
                  </div>
                </div>

                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label>Responsable Actual (ID Profesor o Matrícula Alumno):</label>
                    <input type="text" name="responsable" class="form-control" required
                      oninvalid="this.setCustomValidity('Por favor, complete este campo.')"
                      oninput="this.setCustomValidity('')" <?php if (isset($_GET["id"])) {
                        echo "value='" . $equipo[0]->responsable . "'";
                      } ?>>
                    <small class="form-text text-muted">Debe ser una matrícula de alumno existente o un ID de profesor existente.</small>
                  </div>
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
  $("#fecha_adquisicion").datepicker({
    dateFormat: "yy-mm-dd",
    changeMonth: true,
    changeYear: true
  });
</script>

</html>
