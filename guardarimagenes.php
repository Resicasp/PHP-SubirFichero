<?php
	if($_SERVER['REQUEST_METHOD'] == "GET"){
		echo "<html><body><form enctype='multipart/form-data' method='POST'>
			Enviar este fichero: <input name='archivo1[]' multiple='true' type='file' />
			<input type='submit' value='Enviar fichero'/>
		</form></body></html>";
	}
	else{
		 error_reporting(0);
	    //Definimos los codigos de errores.
	    $codigosErrorSubida= [
	    0 => 'Subida correcta',
	    1 => 'El tamaño del archivo excede el admitido por el servidor',  // directiva upload_max_filesize en php.ini
	    2 => 'El tamaño del archivo excede el admitido por el cliente',  // directiva MAX_FILE_SIZE en el formulario HTML
	    3 => 'El archivo no se pudo subir completamente',
	    4 => 'No se seleccionó ningún archivo para ser subido',
	    6 => 'No existe un directorio temporal donde subir el archivo',
	    7 => 'No se pudo guardar el archivo en disco',  // permisos
	    8 => 'Una extensión PHP evito la subida del archivo'  // extensión PHP
	    ];
	    
	    //Declaración de variables.
	    $mensaje = '';
	    $directorioSubida = "/home/alummo2019-20/Escritorio/PHP/ejercicios04/subirficheros/imgusers";
		//$directorioSubida = "C:\xampp\htdocs\php\img";
	    $tamañototal = 0;
	    $tipoarchivobien = 0;
	    $tamañoarchivobien = 0;
	    $todobien = 0;
	    $todobien2 = 0;
	    
	    //Contador para saber el numero de archivos subidos.
	    $total = count($_FILES['archivo1']['name']);
	    
	    
	    //For para sumar el tamaño de los archivos y para averiguar si el tipo es png o jpeg.
	    for( $i=0 ; $i < $total ; $i++ ) {
	        //Sumamos el tamaño para averiguar el peso total de todos los archivos.
	        $tamanioFichero  =   $_FILES['archivo1']['size'][$i];
	        $tamañototal = $tamañototal + $tamanioFichero/1024;
	        echo $tamañototal."<br>";
	        
	        //Comprobamos que la imagen no pese más de 200. Si no se excede, sumanos
	        //uno a tamañoficherobien.
	        if(($tamanioFichero/1024)<200){
	            $tamañoarchivobien++;
	        }
	        
	        //Averiguamos el tipo del archivo subido. Si esta bien(jpg o png), sumamos uno a tipoarchivobien.
	        if($_FILES['archivo1']['type'][$i] == "image/png" || $_FILES['archivo1']['type'][$i] == "image/jpeg" ){
	            $tipoarchivobien++;
	        }
	    }
	    
	    
	    //Si el numero de los tipos de archivos que esten correctos(png o jpeg)
	    //y el numero de los tamaños de archivos este bien(menor de 200 por cada fichero),
	    //concuerda con el número total de archivos subidos. Cambiamos el valor de todobien
	    //y de todobien2.
	    if($tipoarchivobien==$total){
	        $todobien=1;
	    }
	    if($tamañoarchivobien==$total){
	        $todobien2=1;
	    }
	    
	    
	    //Subimos los ficheros con una serie de comprobaciones previas.
	    if($todobien2==1){
	        if($todobien==1){
	            if($tamañototal<=300){
	                for( $i=0 ; $i < $total ; $i++ ) {
	                    $temporalFichero = $_FILES['archivo1']['tmp_name'][$i];
	                    $nombreFichero   = $_FILES['archivo1']['name'][$i];
	                    $tipoFichero     = $_FILES['archivo1']['type'][$i];
	                    $tamanioFichero  = $_FILES['archivo1']['size'][$i];
	                    $errorFichero    = $_FILES['archivo1']['error'][$i];
	                    
	                    $mensaje .= 'Intentando subir el archivo: ' . ' <br>';
	                    $mensaje .= "- Nombre: $nombreFichero" . ' <br>';
	                    $mensaje .= '- Tamaño: ' . ($tamanioFichero / 1024) . ' KB <br>';
	                    $mensaje .= "- Tipo: $tipoFichero" . ' <br />' ;
	                    //$mensaje .= "- Nombre archivo temporal: $temporalFichero" . ' <br>';
	                    //$mensaje .= "- Código de estado: $errorFichero" . ' <br>';
	                    
	                    
	                    if ($errorFichero > 0) {
	                        $mensaje .= "Se a producido el error: $errorFichero:"
	                        . $codigosErrorSubida[$errorFichero] . ' <br>';
	                    }
	                    else { //Subida correcta del temporal
	                        // Comprobamos si es un directorio y si tengo permisos.
	                        if (is_dir($directorioSubida) && is_writable($directorioSubida)) {
	                            //Comprobamos si existe o no ya el fichero.
	                            if (file_exists($directorioSubida .'/'. $nombreFichero)) {
	                                $mensaje .= "El fichero $nombreFichero ya existe. Por lo que no se ha subido al servidor.<br>";
	                            }
	                            else{
	                                //Movemos el archivo temporal al directorio indicado
	                                if (move_uploaded_file($temporalFichero,  $directorioSubida .'/'. $nombreFichero) == true) {
	                                    $mensaje .= 'Archivo guardado en: ' . $directorioSubida .'/'. $nombreFichero . ' <br><br>';
	                                } else {
	                                    $mensaje .= 'ERROR: Archivo no guardado correctamente. <br>';
	                                }
	                            }
	                        }
	                        else {
	                            $mensaje .= 'ERROR: No es un directorio correcto o no se tiene permiso de escritura. <br>';
	                        }
	                    }
	                }
	                echo $mensaje;
	            }
	            //Else de tamañototal.
	            else{
	                echo "El tamaño de ambos ficheros se excede del limite de subida(300).";
	            }
	        }
	        //Else de todobien(tipoarchivobien)
	        else{
	            if($total == 1){
	                echo "El fichero no es ni png ni jpg.";
	            }else{
	                echo "Alguno de los ficheros no es ni png ni jpg.";
	            }
	        }
	    }
	    //Else de todobien2(tamañoarchivobien)
	    else{
	        if($total == 1){
	            echo "El fichero se excede del limite(200).";
	        }else{
	            echo "Alguno de los ficheros se excede del limite(200).";
	        }
	    }
		
	}	
    ?>