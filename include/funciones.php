<?php
switch ($_POST["acc"]) {
  case '1': #nuevo Registro del XML
    //Obtener variables
    foreach ($_POST as $nombre_campo => $valor) {
      eval ("\$" . $nombre_campo . " = \$_POST[\"" . $nombre_campo . "\"];");
    }
    $xml = simplexml_load_file("../xmlgeneral.xml");
    switch ($tipo) {
      case 1:
        #Insertar Alumno
        //Validar si existe el Alumno
        $dato = $xml->xpath("/facultad/posgrado/maestria/areas/area[@clave='" . $area . "']/alumnos/alumno[matricula='" . $matricula . "']");
        if (count($dato) > 0) {
          //Ya existe
          echo "0";
        } else {
          //Switchar área para obtener índice
          switch ($area) {
            case "BD":
              $areaIndice = 0;
              break;
            case "SD":
              $areaIndice = 1;
              break;
            case "ISI":
              $areaIndice = 2;
              break;
            case "CM":
              $areaIndice = 3;
              break;
          }
          //No existe, realizar inserción
          $alumno = $xml->posgrado->maestria->areas->area[$areaIndice]->alumnos->addChild('alumno');
          $alumno->addChild('matricula', $matricula);
          $alumno->addChild('nombre', $nombre);
          $alumno->addChild('fecha_nac', $fecha_nac);
          $alumno->addChild('edad', $edad);
          $alumno->addChild('tutor', $tutor);
          $alumno->addChild('prom_actual', $promedio);
          $alumno->addChild('creditos', $creditos);
          $alumno->addChild('email', $correo);
          $alumno->addChild('telefono', $telefono);
          $alumno->addChild('genero', $genero);
          $alumno->addChild('no_cvu', $no_cvu);
          $alumno->addChild('curp', $curp);
          $alumno->addChild('rfc', $rfc);
          //Grados académicos
          $grados_academicos = $alumno->addChild('grados_academicos');
          $grado = $grados_academicos->addChild('grado');
          $grado->addChild('titulo', $titulo);
          $grado->addChild('promedio', $promedio);
          $grado->addChild('escuela', $escuela);
          //Materias
          $materias_imp = $alumno->addChild('materias');
          foreach ($materias as $clave_mat) {
            $materia = $materias_imp->addChild('materia');
            $materia->addAttribute('clave_mat', $clave_mat);
          }
          echo $xml->asXML("../xmlgeneral.xml");
        }
        break;

      case 2:
        #Insertar Profesor
        //Validar si existe el Profesor
        $dato = $xml->xpath("/facultad/posgrado/maestria/personal/profesores/profesor[@id_profesor=" . $id_profesor . "]");
        if (count($dato) > 0) {
          //Ya existe
          echo "0";
        } else {
          //No existe, realizar inserción
          $profesor = $xml->posgrado->maestria->personal->profesores->addChild('profesor');
          $profesor->addAttribute('id_profesor', $id_profesor);
          $profesor->addChild('nombre', $nombre);
          $profesor->addChild('ubicacion', $cubiculo);
          $profesor->addChild('correo_electronico', $correo);
          //Publicación
          $publicaciones = $profesor->addChild('publicaciones');
          $publicacion = $publicaciones->addChild('publicacion');
          $publicacion->addChild('autores', $autores);
          $publicacion->addChild('titulo', $titulo_pub);
          $publicacion->addChild('anio', $anio);
          //Materias
          $materias_imp = $profesor->addChild('materias_imp');
          foreach ($materias as $clave_mat) {
            $materia = $materias_imp->addChild('materia');
            $materia->addAttribute('clave_mat', $clave_mat);
          }
          echo $xml->asXML("../xmlgeneral.xml");
        }
        break;

      case 3:
        #Insertar Materia
        //Validar si existe la Materia
        $dato = $xml->xpath("/facultad/posgrado/maestria/materias/materia[clave_mat=" . $clave_mat . "]");
        if (count($dato) > 0) {
          //Ya existe
          echo "0";
        } else {
          //No existe, realizar inserción
          $materia = $xml->posgrado->maestria->materias->addChild('materia');
          $materia->addAttribute('es', "MA");
          $materia->addChild('clave_mat', $clave_mat);
          $materia->addChild('nombre', $nombre);
          $materia->addChild('creditos', $creditos);
          $materia->addChild('horario', $horario);
          $materia->addChild('salon', $salon);
          $materia->addChild('periodo', $periodo);
          echo $xml->asXML("../xmlgeneral.xml");
        }
        break;

      case 4:
        #Insertar Equipo (Inventario)

        // Validar costo > 0
        if ($costo <= 0) {
          echo "COSTO_INVALIDO";
          exit;
        }

        // --- FIX: asegurar nodo inventario antes de usarlo ---
        if (!isset($xml->posgrado->maestria->inventario)) {
          $xml->posgrado->maestria->addChild('inventario');
        }

        //Validar unicidad del No. Inventario
        $dato = $xml->xpath("/facultad/posgrado/maestria/inventario/equipo[no_inventario='" . $no_inventario . "']");
        if (count($dato) > 0) {
          echo "0"; //Ya existe
        } else {
          // --- FIX: limpiar responsable ---
          $responsable = trim($responsable);

          // Validar unicidad del Número de Serie
          $datoSerie = $xml->xpath("/facultad/posgrado/maestria/inventario/equipo[serie='" . $serie . "']");
          if (count($datoSerie) > 0) {
            echo "SERIE_DUPLICADA";
            exit;
          }

          //Validar Responsable (Alumno o Profesor)
          $alumno = $xml->xpath("/facultad/posgrado/maestria/areas/area/alumnos/alumno[matricula='" . $responsable . "']");
          // --- FIX: profesor por atributo numérico (sin comillas) ---
          $profesor = $xml->xpath("/facultad/posgrado/maestria/personal/profesores/profesor[@id_profesor=" . $responsable . "]");

          if (count($alumno) == 0 && count($profesor) == 0) {
            echo "RESPONSABLE_NO_EXISTE";
          } else {
            //Insertar
            $equipo = $xml->posgrado->maestria->inventario->addChild('equipo');
            $equipo->addChild('no_inventario', $no_inventario);
            $equipo->addChild('serie', $serie);
            $equipo->addChild('tipo', $tipo_equipo);
            $equipo->addChild('marca', $marca);
            $equipo->addChild('modelo', $modelo);
            $equipo->addChild('procesador', $procesador);
            $equipo->addChild('fecha_adquisicion', $fecha_adquisicion);
            $equipo->addChild('costo', $costo);
            $equipo->addChild('estado', $estado);
            $equipo->addChild('responsable', $responsable);
            $ok = $xml->asXML("../xmlgeneral.xml");
            echo ($ok ? "1" : "ERROR_GUARDAR_XML");
          }
        }
        break;
    }
    break;

  case '2': #editar Registro del XML
    //Obtener variables
    foreach ($_POST as $nombre_campo => $valor) {
      eval ("\$" . $nombre_campo . " = \$_POST[\"" . $nombre_campo . "\"];");
    }
    //Realizar edición
    $xml = simplexml_load_file("../xmlgeneral.xml");
    switch ($tipo) {
      case 1:
        #Editar Estudiante
        $dato = $xml->xpath("/facultad/posgrado/maestria/areas/area/alumnos/alumno[matricula='" . $id . "']");
        //Eliminar anterior
        unset($dato[0][0]);
        //Switchar área para obtener índice
        switch ($area) {
          case "BD":
            $areaIndice = 0;
            break;
          case "SD":
            $areaIndice = 1;
            break;
          case "ISI":
            $areaIndice = 2;
            break;
          case "CM":
            $areaIndice = 3;
            break;
        }
        //Insertar nuevo
        $alumno = $xml->posgrado->maestria->areas->area[$areaIndice]->alumnos->addChild('alumno');
        $alumno->addChild('matricula', $id);
        $alumno->addChild('nombre', $nombre);
        $alumno->addChild('fecha_nac', $fecha_nac);
        $alumno->addChild('edad', $edad);
        $alumno->addChild('tutor', $tutor);
        $alumno->addChild('prom_actual', $promedio);
        $alumno->addChild('creditos', $creditos);
        $alumno->addChild('email', $correo);
        $alumno->addChild('telefono', $telefono);
        $alumno->addChild('genero', $genero);
        $alumno->addChild('no_cvu', $no_cvu);
        $alumno->addChild('curp', $curp);
        $alumno->addChild('rfc', $rfc);
        //Grados académicos
        $grados_academicos = $alumno->addChild('grados_academicos');
        $grado = $grados_academicos->addChild('grado');
        $grado->addChild('titulo', $titulo);
        $grado->addChild('promedio', $promedio);
        $grado->addChild('escuela', $escuela);
        //Materias
        $materias_imp = $alumno->addChild('materias');
        foreach ($materias as $clave_mat) {
          $materia = $materias_imp->addChild('materia');
          $materia->addAttribute('clave_mat', $clave_mat);
        }
        echo $xml->asXML("../xmlgeneral.xml");
        break;

      case 2:
        #Editar Profesor
        $dato = $xml->xpath("/facultad/posgrado/maestria/personal/profesores/profesor[@id_profesor=" . $id . "]");
        //Eliminar anterior
        unset($dato[0][0]);
        //Insertar nuevo
        $profesor = $xml->posgrado->maestria->personal->profesores->addChild('profesor');
        $profesor->addAttribute('id_profesor', $id);
        $profesor->addChild('nombre', $nombre);
        $profesor->addChild('ubicacion', $cubiculo);
        $profesor->addChild('correo_electronico', $correo);
        //Publicación
        $publicaciones = $profesor->addChild('publicaciones');
        $publicacion = $publicaciones->addChild('publicacion');
        $publicacion->addChild('autores', $autores);
        $publicacion->addChild('titulo', $titulo_pub);
        $publicacion->addChild('anio', $anio);
        //Materias
        $materias_imp = $profesor->addChild('materias_imp');
        foreach ($materias as $clave_mat) {
          $materia = $materias_imp->addChild('materia');
          $materia->addAttribute('clave_mat', $clave_mat);
        }
        echo $xml->asXML("../xmlgeneral.xml");
        break;

      case 3:
        #Editar Materia
        $dato = $xml->xpath("/facultad/posgrado/maestria/materias/materia[clave_mat=" . $id . "]");
        //Eliminar anterior
        unset($dato[0][0]);
        //Insertar nuevo
        $materia = $xml->posgrado->maestria->materias->addChild('materia');
        $materia->addAttribute('es', "MA");
        $materia->addChild('clave_mat', $id);
        $materia->addChild('nombre', $nombre);
        $materia->addChild('creditos', $creditos);
        $materia->addChild('horario', $horario);
        $materia->addChild('salon', $salon);
        $materia->addChild('periodo', $periodo);
        echo $xml->asXML("../xmlgeneral.xml");
        break;

      case 4:
        #Editar Equipo

        // Validar costo > 0
        if ($costo <= 0) {
          echo "COSTO_INVALIDO";
          exit;
        }

        // --- FIX: asegurar nodo inventario ---
        if (!isset($xml->posgrado->maestria->inventario)) {
          $xml->posgrado->maestria->addChild('inventario');
        }

        $dato = $xml->xpath("/facultad/posgrado/maestria/inventario/equipo[no_inventario='" . $id_original . "']");
        if (count($dato) > 0) {
          unset($dato[0][0]); //Borrar el viejo
        }

        // --- FIX: limpiar responsable ---
        $responsable = trim($responsable);

        // Validar unicidad del Número de Serie (excluyendo el registro actual)
        $datoSerie = $xml->xpath("/facultad/posgrado/maestria/inventario/equipo[serie='" . $serie . "' and no_inventario!='" . $id_original . "']");
        if (count($datoSerie) > 0) {
          echo "SERIE_DUPLICADA";
          exit;
        }

        //Validar Responsable
        $alumno = $xml->xpath("/facultad/posgrado/maestria/areas/area/alumnos/alumno[matricula='" . $responsable . "']");
        // --- FIX: profesor por atributo numérico (sin comillas) ---
        $profesor = $xml->xpath("/facultad/posgrado/maestria/personal/profesores/profesor[@id_profesor=" . $responsable . "]");

        if (count($alumno) == 0 && count($profesor) == 0) {
          echo "RESPONSABLE_NO_EXISTE";
        } else {
          $equipo = $xml->posgrado->maestria->inventario->addChild('equipo');
          $equipo->addChild('no_inventario', $no_inventario);
          $equipo->addChild('serie', $serie);
          $equipo->addChild('tipo', $tipo_equipo);
          $equipo->addChild('marca', $marca);
          $equipo->addChild('modelo', $modelo);
          $equipo->addChild('procesador', $procesador);
          $equipo->addChild('fecha_adquisicion', $fecha_adquisicion);
          $equipo->addChild('costo', $costo);
          $equipo->addChild('estado', $estado);
          $equipo->addChild('responsable', $responsable);
          $ok = $xml->asXML("../xmlgeneral.xml");
          echo ($ok ? "1" : "ERROR_GUARDAR_XML");
        }
        break;
    }
    break;

  case '3': #eliminar Registro del XML
    $id = $_POST["id"];
    $tipo = $_POST["tipo"];
    $xml = simplexml_load_file("../xmlgeneral.xml");
    $dato = "";
    switch ($tipo) {
      case 1:
        #Eliminar Estudiante
        $dato = $xml->xpath("/facultad/posgrado/maestria/areas/area/alumnos/alumno[matricula='" . $id . "']");
        unset($dato[0][0]);
        $xml->asXML("../xmlgeneral.xml");
        break;
      case 2:
        #Eliminar Profesor
        $dato = $xml->xpath("/facultad/posgrado/maestria/personal/profesores/profesor[@id_profesor=" . $id . "]");
        unset($dato[0][0]);
        $xml->asXML("../xmlgeneral.xml");
        break;
      case 3:
        #Eliminar Materia
        $dato = $xml->xpath("/facultad/posgrado/maestria/materias/materia[clave_mat=" . $id . "]");
        unset($dato[0][0]);
        $dato = $xml->xpath("/facultad/posgrado/maestria/personal/profesores/profesor/materias_imp/materia[@clave_mat=" . $id . "]");
        for ($i = 0; $i < count($dato); $i++) {
          unset($dato[$i][0]);
        }
        $dato = $xml->xpath("/facultad/posgrado/maestria/areas/area/alumnos/alumno/materias/materia[@clave_mat=" . $id . "]");
        for ($i = 0; $i < count($dato); $i++) {
          unset($dato[$i][0]);
        }
        $xml->asXML("../xmlgeneral.xml");
        break;
      case 4:
        #Eliminar Equipo
        $dato = $xml->xpath("/facultad/posgrado/maestria/inventario/equipo[no_inventario='" . $id . "']");
        unset($dato[0][0]);
        $ok = $xml->asXML("../xmlgeneral.xml");
        echo ($ok ? "1" : "ERROR_GUARDAR_XML");
        break;
    }
    break;
  default:
    //code...
    break;
}
?>
