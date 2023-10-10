<?php

require_once 'config.php';

$tokenJSON = file_get_contents('token.txt');

    // Decodifica el JSON para obtener un array asociativo
    $tokenArray = json_decode($tokenJSON, true);

    // Extrae el valor de 'access_token'
    $refresh_token = $tokenArray['refresh_token'];

$client = new GuzzleHttp\Client(['base_uri' => 'https://login.microsoftonline.com']);
 
$response = $client->request('POST', '/common/oauth2/v2.0/token', [
                'form_params' => [
                    "grant_type" => "refresh_token",
                    "refresh_token" => $refresh_token,
                    "client_id" => ONEDRIVE_CLIENT_ID,
                    "client_secret" => ONEDRIVE_CLIENT_SECRET,
                    "scope" => ONEDRIVE_SCOPE,
                    "redirect_uri" => ONEDRIVE_CALLBACK_URL,
                ],
            ]);

$responseData = $response->getBody()->getContents();

print_r($responseData);