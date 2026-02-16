# Aplicacion_Web_Facultad_XML

## Descripción del proyecto

**Aplicacion_Web_Facultad_XML** es una aplicación web desarrollada como
parte de la materia de Servicios Web. El sistema está basado en una
arquitectura Legacy que utiliza XML como mecanismo principal de
almacenamiento de datos.

La aplicación permite gestionar información académica y administrativa
de una facultad, integrando diferentes módulos dentro de una misma
estructura XML.

Los módulos disponibles son:

   Gestión de estudiantes
   Gestión de profesores
   Gestión de materias
   Módulo de inventario de hardware

El almacenamiento se realiza en el archivo `xmlgeneral.xml`, mientras
que la presentación visual se genera mediante `xmlgeneral.xsl`,
aplicando transformaciones XSLT sobre el XML.

## Tecnologías utilizadas

  PHP (manipulación y validación de datos en backend)
  XML (almacenamiento estructurado de información)
  XSLT (transformación y presentación del XML)
  HTML5 / CSS3
  JavaScript (validaciones y manejo dinámico)
  Bootstrap (diseño responsivo)
  Apache (XAMPP)

> Nota: El proyecto no utiliza base de datos relacional; toda la  persistencia se realiza directamente sobre XML.

## Estructura general del proyecto

-   `inventario.php` → Registro y gestión de equipos (Create, Read,
    Update, Delete)
-   `estudiantes.php` → Gestión de estudiantes
-   `profesores.php` → Gestión de profesores
-   `materias.php` → Gestión de materias
-   `xmlgeneral.xml` → Archivo principal de almacenamiento
-   `xmlgeneral.xsl` → Transformación y diseño visual del XML
-   Carpeta `include/` → Funciones PHP para manipulación del XML
-   Carpeta `css/` y `js/` → Archivos de estilos y scripts de apoyo


## Instalación y ejecución del proyecto

### 1️. Clonar el repositorio

``` bash
git clone URL_DEL_REPOSITORIO
```

### 2️. Copiar el proyecto a XAMPP

Mover la carpeta del proyecto dentro de:

C:`\xampp`{=tex}`\htdocs`{=tex}\

Debe quedar así:

C:`\xampp`{=tex}`\htdocs`{=tex}`\Aplicacion`{=tex}\_Web_Facultad_XML

### 3️. Iniciar XAMPP

1.  Abrir el panel de control de XAMPP
2.  Encender el servicio Apache

### 4. Abrir el proyecto en el navegador

http://localhost/Aplicacion_Web_Facultad_XML


## Arquitectura del sistema

El sistema funciona bajo una arquitectura basada en:

-   XML como almacenamiento centralizado
-   PHP para manipulación del DOM XML
-   XPath para consultas específicas dentro del documento
-   XSLT para la transformación y visualización de la información

El reto principal del proyecto fue extender la arquitectura existente
sin alterar su estructura original.

## Colaboradores

-   Kaltum Abdala Viveros Gómez
-   Nelson Ricardo Sosa Francisco
-   Esmeralda Urbina Cinto
-   Josué Saul Torres Zamora
-   Diego Alberto Nava Rivera
-   Andy Peréz Pavón

## Notas finales

-   El proyecto debe ejecutarse dentro de `htdocs` para que Apache pueda
    acceder correctamente.
-   Se recomienda utilizar ramas (por ejemplo `dev`) para desarrollo
    colaborativo.
-   Se mantiene un archivo de respaldo (`xmlgeneral_respaldo.xml`) para
    preservar la estructura original.

## Información Académica

Benemérita Universidad Autónoma de Puebla (BUAP)  
Facultad de Ciencias de la Computación  

**Docente:** Luis Yael Méndez Sánchez  
**Materia:** Servicios Web  
**Periodo:** Primavera 2026  
