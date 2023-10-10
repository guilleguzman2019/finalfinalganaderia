<?php
require_once 'vendor/autoload.php';
 
define('ONEDRIVE_CLIENT_ID', 'c19ac302-e813-482b-99ac-5fb9c94bd62c');
define('ONEDRIVE_CLIENT_SECRET', 'O6B8Q~l22SCnbJsda95wSyUHqSm1JUu1oA_ggbIn');
define('ONEDRIVE_SCOPE', 'files.read files.read.all files.readwrite files.readwrite.all offline_access');
define('ONEDRIVE_CALLBACK_URL', 'https://desarrollawp.online/auth.php');
 
$config = [
    'callback' => ONEDRIVE_CALLBACK_URL,
    'keys'     => [
                    'id' => ONEDRIVE_CLIENT_ID,
                    'secret' => ONEDRIVE_CLIENT_SECRET
                ],
    'scope'    => ONEDRIVE_SCOPE,
    'authorize_url_parameters' => [
            'approval_prompt' => 'force',
            'access_type' => 'offline'
    ]
];
 
$adapter = new Hybridauth\Provider\MicrosoftGraph( $config );
