<!DOCTYPE html>
<html>
  <head>
    <title>Custom Markers</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" type="text/javascript"></script> 
    <meta charset="utf-8">
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
        width: 80%;
        float: left;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
        .SubMenuOpcionesPolilinea{
            position: absolute;
            bottom: 10px;
            left: 100px;
            z-index: 1000;
            display: none;
        }
        .ulsubmenupolilinea {
            list-style-type: none;
            margin: 0;
            padding: 0;
            width: 200px;
            background-color: #f1f1f1;
        }

        .ulsubmenupolilinea > li a {
            display: block;
            color: #000;
            padding: 8px 16px;
            text-decoration: none;
        }

        /* Change the link color on hover */
        .ulsubmenupolilinea > li a:hover {
            background-color: #555;
            color: white;
        }
        #asignaciones{
            width: 20%;
            float: left;
            height: 100%;
        }

        .ulsubmenupolilinea li:hover > ul {
            display: block;
            left: 200px;
        }
        .ulsubmenupolilinea li > ul {
            display: none;
            position: absolute;
            background-color: #333;
            top: 0;
            list-style-type: none;
            /*left: -200px;
            min-width: 200px;*/
            z-index: -1;
            padding: 0;
            margin: 0;
            /*height: 100%;*/
        }
        .ulsubmenupolilinea li > ul li{
            margin: 0;
            padding: 0;
            left: 0;
        }
        .ulsubmenupolilinea li > ul li a{
            color: white;
        }
        .ulsubmenupolilinea li > ul li a:hover {
            background-color:grey;
            color: black;
        } 

        /*Estilos del tabmenu*/
        * {box-sizing: border-box}


        /* Style the tab */
        div.tab {
            float: left;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
            width: 30%;
            height: 100%;
        }

        /* Style the buttons inside the tab */
        div.tab button {
            display: block;
            background-color: inherit;
            color: black;
            padding: 22px 16px;
            width: 100%;
            border: none;
            outline: none;
            text-align: left;
            cursor: pointer;
            transition: 0.3s;
            font-size: 17px;
        }

        /* Change background color of buttons on hover */
        div.tab button:hover {
            background-color: #ddd;
        }

        /* Create an active/current "tab button" class */
        div.tab button.active {
            background-color: #ccc;
        }

        /* Style the tab content */
        .tabcontent {
            float: left;
            padding: 0px 12px;
            border: 1px solid #ccc;
            width: 70%;
            border-left: none;
            height: 100%;
        }
        .ulcontratos{
            width: 100%;
            /*border: solid 1px blue;*/
            list-style-type: none;
            margin: 0;
            padding: 0;
        }
        .liContratos{
            width: 30%;
            color: black;
            border: solid 1px black;
            float: left;
            display: block;
            text-align: center;

        }
    </style>
  </head>
  <body>
    <div>
        
        <form>
            <input type="file" name="flCoordenadas">
        </form>
        <fieldset>
            <legend>Herramientas</legend>
            <input type="button" name="btnDefault" value="Desplazamiento" onclick="fnMenu('ninguno');">
            <input type="button" name="btnSelecionarPuntos" value="Selecionar Contratos" onclick="fnMenu('seleccionarPuntos');">
        </fieldset>
    </div>
    <div class="SubMenuOpcionesPolilinea" id="SubMenuOpcionesPolilinea">
        <ul class="ulsubmenupolilinea">
            <li><a href="">Asignar A...</a>
                <ul>
                    <li><a href="#" class="clPersonal" id="Persona1">Persona1</a></li>
                    <li><a href="#" class="clPersonal" id="Persona2">Persona2</a></li>
                    <li><a href="#" class="clPersonal" id="Persona3">Persona3</a></li>
                    <li><a href="#" class="clPersonal" id="Persona4">Persona4</a></li>
                    <li>
                        <select name="" id="" style="margin: 1em;">
                            <option value="Trabajador1">Trabajador1</option>
                            <option value="Trabajador2">Trabajador2</option>
                            <option value="Trabajador3">Trabajador3</option>
                            <option value="Trabajador4">Trabajador4</option>
                            <option value="Trabajador5">Trabajador5</option>
                        </select>
                    </li>
                </ul> 
            </li>
            <li><a href="">Eliminar Seleccion</a></li>
            <li><a href="">Opcion1</a></li>
            <li><a href="">Opcion1</a></li>
            <li><a href="">Opcion1</a></li>
        </ul>
    </div>
    <div id="divSeleccionados"></div>
    <div id="map" onmousemove="Ubicaciondelmouse(event)">
    </div>
    <div id="asignaciones">
        
            <!--Aqui se Genera el Menu Dinamicamente--> 
                <!--Aqui se Genera el contenido del Menu Dinamicamente, basado en divs--> 
        
    </div>
    <script>
        /*Comportamiento del tab*/
        function openCity(evt, cityName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }




        var map;
        var features;//contiene los marcadores extraidos del BD
        var iconBase;
        var icons;
        var markers = [];//contiene 
        var MarcadoresSeleccionados= new Array();
        var Menu;
        var PoliniaSelecionador;
        var subMenuPolilineaOpciones;
        var PuntosSelecionados=[]
    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 16,
          center: new google.maps.LatLng(-33.91722, 151.23064),
          mapTypeId: 'roadmap'
        });

        iconBase = 'https://maps.google.com/mapfiles/kml/shapes/';
        icons = {
          parking: {
            icon: iconBase + 'parking_lot_maps.png'
          },
          library: {
            icon: iconBase + 'library_maps.png'
          },
          info: {
            icon: iconBase + 'info-i_maps.png'
          }
        };

        features = [
          {
            position: new google.maps.LatLng(-33.91721, 151.22630),
            type: 'info',
            label: '1',
            nombreMarcador:'1'
          }, {
            position: new google.maps.LatLng(-33.91539, 151.22820),
            type: 'info',
            label: '2',
            nombreMarcador:'2'
          }, {
            position: new google.maps.LatLng(-33.91747, 151.22912),
            type: 'info',
            label: '3',
            nombreMarcador:'3'
          }, {
            position: new google.maps.LatLng(-33.91910, 151.22907),
            type: 'info',
            label: '4',
            nombreMarcador:'4'
          }, {
            position: new google.maps.LatLng(-33.91725, 151.23011),
            type: 'info',
            label: '5',
            nombreMarcador:'5'
          }, {
            position: new google.maps.LatLng(-33.91872, 151.23089),
            type: 'info',
            label: '6',
            nombreMarcador:'6'
          }, {
            position: new google.maps.LatLng(-33.91784, 151.23094),
            type: 'info',
            label: '7',
            nombreMarcador:'7'
          }, {
            position: new google.maps.LatLng(-33.91682, 151.23149),
            type: 'info',
            label: '8',
            nombreMarcador:'8'
          }, {
            position: new google.maps.LatLng(-33.91790, 151.23463),
            type: 'info',
            label: '9',
            nombreMarcador:'9'
          }, {
            position: new google.maps.LatLng(-33.91666, 151.23468),
            type: 'info',
            label: '10',
            nombreMarcador:'10'
          }, {
            position: new google.maps.LatLng(-33.916988, 151.233640),
            type: 'info',
            label: '11',
            nombreMarcador:'11'
          }, {
            position: new google.maps.LatLng(-33.91662347903106, 151.22879464019775),
            type: 'parking',
            label: '12',
            nombreMarcador:'12'
          }, {
            position: new google.maps.LatLng(-33.916365282092855, 151.22937399734496),
            type: 'parking',
            label: '13',
            nombreMarcador:'13'
          }, {
            position: new google.maps.LatLng(-33.91665018901448, 151.2282474695587),
            type: 'parking',
            label: '14',
            nombreMarcador:'14'
          }, {
            position: new google.maps.LatLng(-33.919543720969806, 151.23112279762267),
            type: 'parking',
            label: '15',
            nombreMarcador:'15'
          }, {
            position: new google.maps.LatLng(-33.91608037421864, 151.23288232673644),
            type: 'parking',
            label: '16',
            nombreMarcador:'16'
          }, {
            position: new google.maps.LatLng(-33.91851096391805, 151.2344058214569),
            type: 'parking',
            label: '17',
            nombreMarcador:'17'
          }, {
            position: new google.maps.LatLng(-33.91818154739766, 151.2346203981781),
            type: 'parking',
            label: '18',
            nombreMarcador:'18'
          }, {
            position: new google.maps.LatLng(-33.91727341958453, 151.23348314155578),
            type: 'library',
            label: '19',
            nombreMarcador:'19'
          }
        ];
        /*PoliniaSelecionador = new google.maps.Polygon({
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#FF0000',
            fillOpacity: 0.35,
            editable: true,
            draggable: true
        });*/
        // Create markers.
        var coordenadasruta= new Array();
        var flightPath;
        //var marker;
       /* features.forEach(function(feature) {
            AgregarMarcador(feature.position,icons[feature.type].icon,feature.label,feature.nombreMarcador,map);

        });*/
        MostrarMarcadores();
        
        // Handles click events on a map, and adds a new point to the Polyline.
        
        

        //Funciones
        function AgregarMarcador(position,icon,label,nombreMarcador,map){
            var marker = new google.maps.Marker({
                position: position,
                icon: icon,
                label: label,
                map: map,
                nombreMarcador:nombreMarcador
            });
            //Este array deberia de existir previamente
            markers.push(marker);
            //console.log(markers.length);
            // cuando se haga doble click se eliminara del mapa y del array
            marker.addListener('dblclick', function() {
                marker.setMap(null);
                ////console.log(marker);
            });
        }
        function EstablecerMarcadores(map) {
            /*for (var i = 0; i < markers.length; i++) {
              markers.setMap(map);
            }*/
            /*features.forEach(function(feature) {
                AgregarMarcador(feature.position,icons[feature.type].icon,feature.label,feature.nombreMarcador,map);
            });*/
            for (var i in features) {
                if (features[i].hasOwnProperty('nombreMarcador')) {
                  //features[i][j].check = true;
                  //console.log(features[i].nombreMarcador);
                  //features[i].setMap(map);
                  AgregarMarcador(features[i].position,icons[features[i].type].icon,features[i].label,features[i].nombreMarcador,map);
              }
            }
        }
        function OcultarMarcadores() {
            MostrarMarcadores(null);
        }
        // Muestra todos los marcadores que estan en el array, en el mapa.
        function MostrarMarcadores() {
            EstablecerMarcadores(map);
        }
        // Eliminar todos los marcadores del array y oculta los marcadores. Dando la sencion de que se eliminaron
        function DeleteMarkers() {
            OcultarMarcadores();
            markers = [];
        }
    }
    function fnMenu(opcionMenu) {
        /*Menu de Opciones*/
        switch(opcionMenu) {
            case 'seleccionarPuntos':
                document.getElementById("SubMenuOpcionesPolilinea").style.display="none";
                NombreAleatorio=generarNombreAleatorio(5);
                
                map.addListener('click', addLatLng);
                DibujarPilininea();
                PoliniaSelecionador.addListener('rightclick',subMenuPolilinea);
                //PoliniaSelecionador.addListener('dblclick',fnAsignarPolilinea);
                break;
            case 'ninguno':
                google.maps.event.clearListeners(map, 'click');
                fnOcultarSubmenu();
                break;
            default:
                //code block
        }
    }
    var NombreAleatorio;
    var divMenuAgrupado;
    var divContenidoAgrupado;
    
    function generarNombreAleatorio(longitud){
        var caracteres = "abcdefghijkmnpqrtuvwxyzABCDEFGHIJKLMNPQRTUVWXYZ2346789";
        var NombreAleatorio = "";
        for (i=0; i<longitud; i++) NombreAleatorio += caracteres.charAt(Math.floor(Math.random()*caracteres.length));
        return NombreAleatorio;
    }
    function addLatLng(event) {
        MarcadoresSeleccionados=new Array();
        var path = PoliniaSelecionador.getPath();
        // Because path is an MVCArray, we can simply append a new coordinate
        // Agregamos el nuevo nuevo.
        path.push(event.latLng);
        features.forEach(function(feature) {
            if (google.maps.geometry.poly.containsLocation(feature.position, PoliniaSelecionador)) {
                /*var marker = new google.maps.Marker({
                    position: feature.position,
                    icon: "https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png",
                    label: feature.label,
                    map: map
                });*/
                MarcadoresSeleccionados.push(feature.nombreMarcador);
                //PuntosSelecionados[0].idTrabajador.contratos.push(feature.nombreMarcador);
                //console.log(MarcadoresSeleccionados);
            }
        });
       // function obtener(valor){
        if (PuntosSelecionados.length<=0) {
            PuntosSelecionados.push({
                idTrabajador:NombreAleatorio,
                contratos:MarcadoresSeleccionados
            })
        }
        var contadorIncoincidente=0;
        //Verificando que no haya el mismo ID para que no sea repetido
        for(var i = 0; i < PuntosSelecionados.length; i++){
            if(PuntosSelecionados[i].idTrabajador == NombreAleatorio){
                PuntosSelecionados[i].contratos=MarcadoresSeleccionados;
            }else{
                contadorIncoincidente+=1;
            }
        }

        //alert(contadorIncoincidente);
        if (contadorIncoincidente==PuntosSelecionados.length) {
            PuntosSelecionados.push({
                idTrabajador:NombreAleatorio,
                contratos:MarcadoresSeleccionados
            })
            contadorIncoincidente=0;
        }
        //Creando el menu de agrupamiento
        fnRepresentarSeleccionado();
        //PuntosSelecionados[0].idTrabajador[0].contratos.push();
        console.log(PuntosSelecionados);
        /*var resultColor =
            google.maps.geometry.poly.containsLocation(e.latLng, PoliniaSelecionador) ?
            'red' :
            'green';
        new google.maps.Marker({
        position: e.latLng,
        map: map,
        icon: {
            path: google.maps.SymbolPath.CIRCLE,
            fillColor: resultColor,
            fillOpacity: .2,
            strokeColor: 'white',
            strokeWeight: .5,
            scale: 10
        }*/
    }
    function DibujarPilininea() {
        /*Intentando dibujar polilinea*/
        PoliniaSelecionador = new google.maps.Polygon({
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#FF0000',
            fillOpacity: 0.35,
            editable: true,
            draggable: true,
            NombrePolilinea:NombreAleatorio
        });
        PoliniaSelecionador.setMap(map);
        
    }
    function subMenuPolilinea(event) {
        // body...
        document.getElementById("SubMenuOpcionesPolilinea").style.display="block";
        document.getElementById("SubMenuOpcionesPolilinea").style.top=varMouseY+"px";
        document.getElementById("SubMenuOpcionesPolilinea").style.left=varMouseX+"px";
        //alert(event.clientY);
        google.maps.event.clearListeners(map, 'click');
        map.addListener('click', function() {
            fnOcultarSubmenu();
        });
        //Asignando segun el valor de los controles DOM
        var x = document.getElementsByClassName("clPersonal");
        var elem=this;
        var actu;
        //for ( var i = 0; i < x.length; i++) {
            //x[i].onclick  = fnAsignarPolilinea(this.NombrePolilinea,x[i].id,x[i].innerHTML);
            //actu=x[i];
            /*x[i].onclick  = function () {
                //alert("sdafasdf");
                fnAsignarPolilinea(elem,elem.NombrePolilinea,actu.innerHTML,actu.innerHTML);
            }*/
            //x[i].setAttribute("onClick", "fnAsignarPolilinea("+elem+",'"+elem.NombrePolilinea+"','"+actu.innerHTML+"','"+actu.innerHTML+"')");
            //x[i].setAttribute("onClick", fnAsignarPolilinea(elem,elem.NombrePolilinea,actu.innerHTML,actu.innerHTML));
            /*$(x[i]).on( "click", function( event ) {
              fnAsignarPolilinea(elem,elem.NombrePolilinea,actu.innerHTML,actu.innerHTML)
            });*/
            $(".clPersonal").click(function() {
                fnAsignarPolilinea(elem,elem.NombrePolilinea,this.innerHTML,this.innerHTML)
            });
        //}
        
        //fnAsignarPolilinea(this.NombrePolilinea,generarNombreAleatorio(5),"Trabajador1");
        //alert(this.NombrePolilinea);
    }
    function fnAsignarPolilinea(elemento,idTrabajador,NuevoIDTrabajador,NombreTrabajador) {
        elemento.NombrePolilinea=NuevoIDTrabajador;
        //console.log("idTrabajador: "+idTrabajador+", NuevoIDTrabajador: "+NuevoIDTrabajador+" ,NombreTrabajador: "+NombreTrabajador);
        //alert(document.getElementsByName(idTrabajador).value);
        for(var i = 0; i < PuntosSelecionados.length; i++){
            if(PuntosSelecionados[i].idTrabajador == idTrabajador){
                PuntosSelecionados[i].idTrabajador=NuevoIDTrabajador;
                PuntosSelecionados[i].NombrePolilinea=NombreTrabajador;
            }
        }
        //console.log(elemento);
        fnOcultarSubmenu();
        fnRepresentarSeleccionado();
    }
    function fnOcultarSubmenu() {
        document.getElementById("SubMenuOpcionesPolilinea").style.display="none";
    }
    var varMouseX;
    var varMouseY;
    function Ubicaciondelmouse(event) {
        varMouseX= event.clientX;
        varMouseY= event.clientY;
    }
    function fnRepresentarSeleccionado() {
        //document.getElementById("asignaciones").innerHTML="";
        divMenuAgrupado="";
        divContenidoAgrupado="";
        var varContratosAgrupado="";
        for(var i = 0; i < PuntosSelecionados.length; i++){
            //divMenuAgrupado+="<input type='button' value='"+PuntosSelecionados[i].idTrabajador+"' class='tablinks' ondblclick='alert(this.value);' onclick=\"openCity(event, '"+PuntosSelecionados[i].idTrabajador+"')\">";
            divMenuAgrupado+="<button class='tablinks' ondblclick='alert(this.name);' onclick=\"openCity(event, '"+PuntosSelecionados[i].idTrabajador+"')\" name='"+PuntosSelecionados[i].idTrabajador+"'>"+PuntosSelecionados[i].idTrabajador+"</button>";
            varContratosAgrupado="";
            PuntosSelecionados[i].contratos.forEach(function(word) {
              //console.log(word);
              varContratosAgrupado=varContratosAgrupado+"<li class='liContratos'>"+word+"</li> ";//Es importante el espacio en blanco al final del span para que se tabule autmaticamente
            });
            divContenidoAgrupado+="<div id='"+PuntosSelecionados[i].idTrabajador+"' style='display:none;' class='tabcontent'><h3>"+PuntosSelecionados[i].idTrabajador+"</h3><ul class='ulcontratos'>"+varContratosAgrupado+"</ul></div>"
            //document.getElementById(PuntosSelecionados[i].idTrabajador).style.display = "none";
        }
        //}
        document.getElementById("divSeleccionados").innerHTML="<pre>"+JSON.stringify(PuntosSelecionados)+"</pre>";
        //divMenuAgrupado="<button class='tablinks' onclick=\"openCity(event, '"+NombreAleatorio+"')\">"+NombreAleatorio+"</button>";
        //document.getElementById("divAgrupados").innerHTML="<div class='tab' id='divAgrupados'>"+divMenuAgrupado+"</div>";
        document.getElementById("asignaciones").innerHTML="<div class='tab' id='divAgrupados'>"+divMenuAgrupado+"</div>"+divContenidoAgrupado;
    }
    </script>
    <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCyeNELJuURtBnMQR5Josan3KL7luObvlg&callback=initMap">
    </script>
  </body>
</html>