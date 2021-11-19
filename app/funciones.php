<?php
include_once 'app/config.php';


// Cargo los datos segun el formato de configuración
function cargarDatos(){
    $funcion =__FUNCTION__.TIPO; // cargarDatostxt
    return $funcion();
}

function volcarDatos($valores){
    $funcion =__FUNCTION__.TIPO;
    $funcion($valores);
}

// ----------------------------------------------------
// FICHERO DE TEXT 
//Carga los datos de un fichero de texto
function cargarDatostxt(){
    // Si no existe lo creo
    $tabla=[]; 
    if (!is_readable(FILEUSER) ){
        // El directorio donde se crea tiene que tener permisos adecuados
        $fich = @fopen(FILEUSER,"w") or die ("Error al crear el fichero.");
        fclose($fich);
    }
    $fich = @fopen(FILEUSER, 'r') or die("ERROR al abrir fichero de usuarios"); // abrimos el fichero para lectura
    
    while ($linea = fgets($fich)) {
        $partes = explode('|', trim($linea));
        $tabla[]= [$partes[0],$partes[1],$partes[2],$partes[3]];
        }
    fclose($fich);
    
    return $tabla;
}

//Vuelca los datos a un fichero de texto
function volcarDatostxt($tvalores){
   $file1= @fopen(FILEUSER, "w");
    foreach($tvalores as $lineas) {
        $lineas = implode("|", $lineas) . "\n";
        fwrite($file1, $lineas);
    }
    fclose($file1); 
} 

//leer cada línea del array y volcarlo en un fichero

// ----------------------------------------------------
// FICHERO DE CSV

function cargarDatoscsv (){
    if (!is_readable(FILEUSER) ){
        // El directorio donde se crea tiene que tener permisos adecuados
        $fich = @fopen(FILEUSER,"w") or die ("Error al crear el fichero.");
        fclose($fich);
    }
    $fich = @fopen(FILEUSER, 'r') or die("ERROR al abrir fichero de usuarios"); // abrimos el fichero para lectura


    
    $lines = array();
    while(!feof($fich) && ($line = fgetcsv($fich, 1000, ","))) {
        $lines[] = $line;
    }

    
    return $lines;
}
   

//Vuelca los datos a un fichero de csv
function volcarDatoscsv($tvalores){

    $file= @fopen(FILEUSER, "w");
    foreach($tvalores as $lineas) {
        fputcsv($file, $lineas);
    }

}

// ----------------------------------------------------
// FICHERO DE JSON
function cargarDatosjson (){
    if (!is_readable(FILEUSER) ){
        // El directorio donde se crea tiene que tener permisos adecuados
        $fich = @fopen(FILEUSER,"w") or die ("Error al crear el fichero.");
        fclose($fich);
    }
    $fich = @fopen(FILEUSER, 'r') or die("ERROR al abrir fichero de usuarios"); // abrimos el fichero para lectura
    
    $data = file_get_contents(FILEUSER);
    $fich = json_decode($data, true);

   return $fich;
  
}

function volcarDatosjson($tvalores){
    $tvalores = json_encode($tvalores);
    file_put_contents(FILEUSER, $tvalores) or die ("Error al escribir en el fichero."); 
}


// MOSTRA LOS DATOS DE LA TABLA DE ALMACENADA EN AL SESSION 
// se recorre como un array de objetos
function mostrarDatos (){
    
    $titulos = [ "Nombre","login","Password","Comentario"];
    $msg = "<table>\n";
    $msg .= "<tr>";
    for ($j=0; $j < CAMPOSVISIBLES; $j++){
        $msg .= "<th>$titulos[$j]</th>";
    }  
    $msg .= "</tr>";
    $auto = $_SERVER['PHP_SELF'];
    $id=0;
    $nusuarios = count($_SESSION['tuser']); 
    for($id=0; $id< $nusuarios ; $id++){
        $msg .= "<tr>";
        $datosusuario = $_SESSION['tuser'][$id];
        for ($j=0; $j < CAMPOSVISIBLES; $j++){
            $msg .= "<td>$datosusuario[$j]</td>"; 
        }
        $msg .="<td><a href=\"#\" onclick=\"confirmarBorrar('$datosusuario[0]',$id);\" >Borrar</a></td>\n"; 
        $msg .="<td><a href=\"".$auto."?orden=Modificar&id=$id\">Modificar</a></td>\n";
        $msg .="<td><a href=\"".$auto."?orden=Detalles&id=$id\" >Detalles</a></td>\n";  
        $msg .="</tr>\n";
        
    }
    $msg .= "</table>";
   
    return $msg;    
}

// Función para limpiar todos elementos de un array
function limpiarArrayEntrada(array &$entrada){

    $entrada = htmlspecialchars($_REQUEST['nombre']);
    $entrada = htmlspecialchars($_REQUEST['login']);
    $entrada = htmlspecialchars($_REQUEST['clave']);
    $entrada = htmlspecialchars($_REQUEST['comentario']);
    
}

