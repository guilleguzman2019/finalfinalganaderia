<?php


$tokenJSON = file_get_contents('token.txt');

if ($tokenJSON !== false) {
    $tokenArray = json_decode($tokenJSON, true);

    if ($tokenArray !== null && isset($tokenArray['access_token'])) {
        $accessToken = $tokenArray['access_token'];
    }
} else {
    
    $accessToken = null;
}

$client_id = 'c19ac302-e813-482b-99ac-5fb9c94bd62c'; // Reemplaza con tu Client ID
$redirect_uri = 'https://desarrollawp.online/auth.php'; // Reemplaza con tu URL de redirección
$scopes = 'files.read files.read.all files.readwrite files.readwrite.all offline_access'; // Los permisos que necesitas
$client_secret = 'O6B8Q~l22SCnbJsda95wSyUHqSm1JUu1oA_ggbIn';

if (!isset($_GET['code']) && !isset($accessToken)) {


    $authorization_url = 'https://login.microsoftonline.com/81fccec2-2dba-4a80-b4c4-491622dd982e/oauth2/v2.0/authorize?' . http_build_query([
        'client_id' => $client_id,
        'redirect_uri' => $redirect_uri,
        'response_type' => 'code', // Utilizar "code" para el flujo de autorización de código
        'scope' => $scopes,
    ]);


    echo '<a href="'.$authorization_url.'" class="button" style="border-radius: 8px;padding: 7px;background-color: black;color: white; text-decoration: none;">Autenticarse en Microsoft 😎</a>';



} elseif (isset($_GET['code']) && !isset($accessToken)) {
    // Hacer el auth token api microsoft
    $code = $_GET['code'];

    $token_url = 'https://login.microsoftonline.com/81fccec2-2dba-4a80-b4c4-491622dd982e/oauth2/v2.0/token';

    // Parámetros de la solicitud
    $token_params = [
        'grant_type' => 'authorization_code',
        'client_id' => $client_id,
        'client_secret' => $client_secret, // Solo necesario si tu aplicación es confidencial
        'redirect_uri' => $redirect_uri,
        'code' => $code,
    ];

    // Realizar la solicitud POST
    $ch = curl_init($token_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($token_params));
    $response = curl_exec($ch);
    curl_close($ch);

    // Decodificar la respuesta JSON
    //$token_data = json_decode($response, true);

    // Token de acceso
    //$access_token = $token_data['access_token'];

    $archivoToken = 'token.txt';

    // Abre el archivo en modo escritura
    $file = fopen($archivoToken, 'w');

    // Escribe el token en el archivo
    if ($file) {
        fwrite($file, json_encode($response));
        fclose($file);
        header('Location: listado.php');
        exit();
    } else {
        echo "Error al abrir el archivo para escritura.";
    }

    

} elseif (isset($accessToken)) {
    // Redirigir a listado.php
    header('Location: listado.php');
    exit();
}
?>




