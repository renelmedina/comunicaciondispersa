<!-- http://ProgramarEnPHP.wordpress.com -->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>:: Importar de Excel a la Base de Datos ::</title>
</head>

<body>
    holas
    

    <!-- FORMULARIO PARA SOICITAR LA CARGA DEL EXCEL -->
    Selecciona el archivo a importar:
    <form name="importa" method="post" action="importar.php" enctype="multipart/form-data" >
        <input type="file" name="excel" />
        <input type='submit' name='enviar'  value="Importar"  />
        <input type="hidden" value="upload" name="action" />
    </form>
    <!-- CARGA LA MISMA PAGINA MANDANDO LA VARIABLE upload -->
    <?php
    extract($_POST);
    $action=$_POST["action"];
    if ($action == "upload") {
        //cargamos el archivo al servidor con el mismo nombre
        //solo le agregue el sufijo bak_ 
        $archivo = $_FILES['excel']['name'];
        $tipo = $_FILES['excel']['type'];
        $destino = "bak_" . $archivo;
        if (copy($_FILES['excel']['tmp_name'], $destino)){
            echo "Archivo Cargado Con Éxito";
        }
        else{
            echo "Error Al Cargar el Archivo";
        }
        if (file_exists("bak_" . $archivo)) {
            /** Clases necesarias */
            require_once('Classes/PHPExcel.php');
            require_once('Classes/PHPExcel/Reader/Excel2007.php');
            // Cargando la hoja de cálculo
            $objReader = new PHPExcel_Reader_Excel2007();
            $objPHPExcel = $objReader->load("bak_" . $archivo);
            $objFecha = new PHPExcel_Shared_Date();
            // Asignar hoja de excel activa
            $objPHPExcel->setActiveSheetIndex(0);
            //conectamos con la base de datos 
            /*$cn = mysql_connect("localhost", "root", "") or die("ERROR EN LA CONEXION");
            $db = mysql_select_db("prueba", $cn) or die("ERROR AL CONECTAR A LA BD");*/
            // Llenamos el arreglo con los datos  del archivo xlsx
            $FilasTotales=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
            for ($i = 2; $i <= 100; $i++) {//$FilasTotales el total aceptado es 1303
                $_DATOS_EXCEL[$i]['contrato'] = $objPHPExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['nim'] = $objPHPExcel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['tipo'] = $objPHPExcel->getActiveSheet()->getCell('C' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['nombres'] = $objPHPExcel->getActiveSheet()->getCell('D' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['direccion'] = $objPHPExcel->getActiveSheet()->getCell('E' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['sed'] = $objPHPExcel->getActiveSheet()->getCell('F' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['longitud'] = $objPHPExcel->getActiveSheet()->getCell('G' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['latitud'] = $objPHPExcel->getActiveSheet()->getCell('H' . $i)->getCalculatedValue();
            }
            echo "<br>filas Actuales:".$FilasTotales."<br>";
        }
        //si por algo no cargo el archivo bak_ 
        else {
            echo "Necesitas primero importar el archivo";
        }
        $errores = 0;
        //recorremos el arreglo multidimensional 
        //para ir recuperando los datos obtenidos
        //del excel e ir insertandolos en la BD
        echo "<table>";
        $coordenadas="";
        foreach ($_DATOS_EXCEL as $campo => $valor) {
            /*$sql = "INSERT INTO datos VALUES (NULL,'";
            foreach ($valor as $campo2 => $valor2) {
                $campo2 == "direccion" ? $sql.= $valor2 . "');" : $sql.= $valor2 . "','";
            }
            echo $sql;
            $result = mysql_query($sql);
            if (!$result) {
                echo "Error al insertar registro " . $campo;
                $errores+=1;
            }*/
            echo "<tr>";
            echo "<td>".$valor["nombres"]."</td>";
            echo "<td>".$valor["direccion"]."</td>";
            echo "</tr>";
            $coordenadas.="{lat: ".$valor["longitud"].", lng: ".$valor["latitud"]."},";
           // $coordenadas="{lat: -16.4018716666666, lng: -71.5400216666666}";

            //echo $valor["direccion"];

        }
        echo "</table>";
        echo "<strong><center>ARCHIVO IMPORTADO CON EXITO, EN TOTAL $campo REGISTROS Y $errores ERRORES</center></strong>";
        //una vez terminado el proceso borramos el archivo que esta en el servidor el bak_
        unlink($destino);
    }
    ?>


    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        font-family: verdana;
        font-size: 12px;
      }
      #DetallesLocal{
        width: 300px;
      }
      #map {
        height: 400px;
        width: 800px;
      }
      table{
        border: none;

      }
      table tr:hover td{
        background-color: #fff;
        color: black;
      }
      table td{
        border: none;
      }
    </style>
    <div id="map"></div>
    <script>

      function initMap() {

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 3,
          center: {lat: -28.024, lng: 140.887}
        });

        // Create an array of alphabetical characters used to label the markers.
        var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // Add some markers to the map.
        // 
        // Note: The code uses the JavaScript Array.prototype.map() method to
        // create an array of markers based on a given "locations" array.
        // The map() method here has nothing to do with the Google Maps API.
        var markers = locations.map(function(location, i) {
          return new google.maps.Marker({
            position: location,
            label: labels[i % labels.length]
          });
            this.addListener('click', function() {
                marker.setIcon('https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png');
            });
        });

        // Add a marker clusterer to manage the markers.
        var markerCluster = new MarkerClusterer(map, markers,
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
      }
      var locations = [
        <?php echo $coordenadas;?>
      ]
    </script>
    <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
    </script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCyeNELJuURtBnMQR5Josan3KL7luObvlg&callback=initMap">
    </script>
    <?php echo $coordenadas;?>
</body>
</html>