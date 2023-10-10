<?php

session_start();

// Verifica si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    header('Location: index.html'); // Redirige a la página de inicio de sesión si no está autenticado
    exit();
}

// Si el usuario hace clic en "Cerrar Sesión"
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: index.html'); // Redirige a la página de inicio de sesión después de cerrar sesión
    exit();
}

require_once 'config.php';

echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Tabla con Selects</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>

        body {
            font-family: "Montserrat", sans-serif;
        }

        .bg-verde-manzana {
            background-color: #001c2a;
            color:white;
        }
        .text-verde-manzana {
            color: white;
        }

        thead{

            background-color: #001c2a;
            color:white;
        }

        .select-group .form-control {
            display: inline-block;
            width: 90%;
            margin-right: 10px;
        }

        .floating-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #007bff;
            color: #fff;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            text-align: center;
            font-size: 30px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .floating-button-icon {
            font-size: 20px;
            margin-top: 15px;
        }

        .oculto{
            display:none; class="oculto"
        }

        .btn-primary{
            background-color: #001c2a !important;
            padding: 10px;
            border-radius: 15px !important;
            color:white !important;
        }

        .imagen{
            margin: 0 auto;
        }

        .newPeso{
            border: none;
            border-radius: 12px;
            padding: 10px;
            background-color: #80808021;
        }

        .backgroud-final{

            background-color: #808080 !important;
        }

        select option{

            background-color: #001c2a !important;
        }

        select :hover{
            background-color: #001c2a !important;
        }



    </style>
</head>
<body class="">
    <div class="container mt-4">
        <div class="bg-titulo text-center mb-5 d-block d-sm-flex">

            <!-- Logo -->
            <img class="imagen pb-5" src="https://i.postimg.cc/tCZxX0FF/pradia.jpg" alt="Logo" width="300">
            <br>
            <form action="logout.php" method="post">
                <button class="btn-primary text-light" type="submit" name="logout">Cerrar Sesión</button>
            </form>
        </div>
        <div class="row mb-4">
            <div class="col-md-4 select-group">
                <label>Fecha</label>
                <br>
                <input class="form-control bg-verde-manzana text-light" type="date" id="fecha" name="fecha">
                <button type="button" class="btn bg-verde-manzana m-1" id="resetFiltros">Reset</button>
            </div>
            <div class="col-md-4 select-group">
                <label>Finca</label>
                <select id="finca" class="form-control bg-verde-manzana text-light">
                    <option value="">TODOS</option>
                </select>
            </div>
            <div class="col-md-4 select-group">
                <label>Manada</label>
                <select id="manada" class="form-control bg-verde-manzana text-light">
                <option value="">TODOS</option>
                </select>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th class="backgroud-final">Código Animal</th>
                                <th class="backgroud-final">Fecha</th>
                                <th class="backgroud-final">Peso</th>
                                <th>Nuevo Peso</th>
                            </tr>
                        </thead>
                        <tbody>';
 
 
append_to_sheet();

 
function append_to_sheet() {

    $item_id = '013HAXRPK2J2SLW2HVMRG233RJUFWODTE2';
    $table = 'Table1';


    $archivo = fopen('token.txt', 'r');
    $contenido = fread($archivo, filesize('token.txt'));
    fclose($archivo);

    // Decodifica el JSON para obtener un array asociativo
    $tokenArray = json_decode($contenido, true);

   var_dump($tokenArray);

    // Extrae el valor de 'access_token'
    $accessToken = $tokenArray['access_token'];
  
 
    try {
 
        $client = new GuzzleHttp\Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://graph.microsoft.com',
        ]);


        $response = $client->request("GET", "/v1.0/me/drive/items/013HAXRPK2J2SLW2HVMRG233RJUFWODTE2/workbook/tables/Table1/rows/", [
            'headers' => [
                'Authorization' => 'Bearer '. $accessToken
            ],
            'verify' => false,
        ]);

        $responseData = json_decode($response->getBody(), true);


        $arrayRow = [];
        $arrayFinca = [];
        $arrayManada = [];


        if (isset($responseData['value'])) {
            foreach ($responseData['value'] as $index => $row) {

                $codigo = $row['values'][0][1];
                $peso= $row['values'][0][6];
                $fecha= $row['values'][0][4];
                $manada= $row['values'][0][13];
                array_push($arrayManada, $manada);
                $finca= $row['values'][0][0];
                array_push($arrayFinca, $finca);


                echo '<tr id="'.$index.'" class="text-center">
                        <td>'.$codigo.'</td>
                        <td>'.$fecha.'</td>
                        <td class="">'.$peso.'</td>
                        <td class="oculto">'.$manada.'</td>
                        <td class="oculto">'.$finca.'</td>
                        <td style="width:150px;">
                            <input class="newPeso" type="text" id="NewPeso" name="NewPeso">
                        </td>
                    </tr>';


            }
        }

        $jsonFinca = json_encode($arrayFinca);
        $jsonManada = json_encode($arrayManada);

        echo '</tbody>
                        </table>
                    </div>
                </div>
                </div>
                </div>

                <!-- Modal para editar -->
                <div class="modal fade" id="editarModal" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editarModalLabel">Editar Animal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="codigo">Código:</label>
                                <input type="text" class="form-control" id="codigo" placeholder="Código del Animal">
                            </div>
                            <div class="form-group">
                                <label for="peso">Peso:</label>
                                <input type="text" class="form-control" id="peso" placeholder="Peso del Animal">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary guardarCambiosBtn">Guardar Cambios</button>
                    </div>
                </div>
                </div>
                </div>

                <!-- Modal para agregar nueva fila -->
                <div class="modal fade" id="agregarModal" tabindex="-1" role="dialog" aria-labelledby="agregarModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="agregarModalLabel">Nuevo Animal</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="form-group">
                                        <label for="codigoNuevo">Código:</label>
                                        <input type="text" class="form-control" id="codigoNuevo" placeholder="Código del Animal">
                                    </div>
                                    <div class="form-group">
                                        <label for="pesoNuevo">Peso:</label>
                                        <input type="text" class="form-control" id="pesoNuevo" placeholder="Peso del Animal">
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary agregarFilaBtn">Agregar Nuevo</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botón flotante para agregar fila -->
                <div class="floating-button" data-toggle="modal" data-target="#agregarModal">
                    <div class="floating-button-icon">+</div>
                </div>

                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js" integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
                <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
                <script>
                  var accessToken = "'. $accessToken.'";
                  var arrayFinca = '. $jsonFinca.';
                  var arrayManada = '. $jsonManada.';

                  var arraySinDuplicadosFinca = arrayFinca.filter(function(item, index) {
                    return arrayFinca.indexOf(item) === index;
                });

                var arraySinDuplicadosManada = arrayManada.filter(function(item, index) {
                    return arrayManada.indexOf(item) === index;
                });

                  var selectManada = document.getElementById("manada");

                    // Iterar sobre el array y agregar opciones al select
                    arraySinDuplicadosManada.forEach(function(opcion) {
                        var option = document.createElement("option");
                        option.value = opcion;
                        option.text = opcion;
                        selectManada.add(option);
                    });

                    var selectFinca = document.getElementById("finca");

                    // Iterar sobre el array y agregar opciones al select
                    arraySinDuplicadosFinca.forEach(function(opcion) {
                        var option = document.createElement("option");
                        option.value = opcion;
                        option.text = opcion;
                        selectFinca.add(option);
                    });

                </script>
                <script src="script.js"></script>
                </body>
                </html>';


    } catch(Exception $e) {

    $archivo = fopen('token.txt', 'r');
    $contenido = fread($archivo, filesize('token.txt'));
   fclose($archivo);

    $tokenArray = json_decode($contenido, true);

    // Extrae el valor de 'access_token'
    $refresh_token = $tokenArray['refresh_token'];

    $client = new GuzzleHttp\Client(['base_uri' => 'https://login.microsoftonline.com']);
    
    $response = $client->request('POST', '/common/oauth2/v2.0/token', [
                    'form_params' => [
                        "grant_type" => "refresh_token",
                        "refresh_token" => $refresh_token,
                        "client_id" => 'c19ac302-e813-482b-99ac-5fb9c94bd62c',
                        "client_secret" => 'O6B8Q~l22SCnbJsda95wSyUHqSm1JUu1oA_ggbIn',
                        "scope" => 'files.read files.read.all files.readwrite files.readwrite.all offline_access',
                        "redirect_uri" => 'https://desarrollawp.online/auth.php',
                    ],
                ]);

    $responseData = $response->getBody()->getContents();

    $jsonData = json_decode($responseData, true);

    $archivoToken = 'token.txt';

    // Abre el archivo en modo escritura
    $file = fopen($archivoToken, 'w');

    // Escribe el token en el archivo
    if ($file) {
        fwrite($file, json_encode($jsonData));
        fclose($file);
    }

    append_to_sheet();

        
    }
}



