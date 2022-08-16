<?php 

    //pega o conteudo do body
    $body = file_get_contents('php://input');

    $requestUri = $_SERVER['REQUEST_URI'];
    $httpPos = strpos($requestUri, 'http');
    $ishttps = strpos($requestUri, 'https')>0;
    $url = substr($requestUri, $httpPos, strlen($requestUri)-$httpPos);
    $url = strtr($url, array('http//'=>'http://', 'https//'=>'https://'));
    // print_r($url);
    // var_dump(getallheaders());

    $headers = getallheaders();
    //pega  o verbo do request
    $verb = $_SERVER['REQUEST_METHOD'];

    //carrega dados do site $url
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    switch ($verb) {
        case 'GET':
            curl_setopt($ch, CURLOPT_HTTPGET, true);
            break;
        case 'POST':
            curl_setopt($ch, CURLOPT_POST, true);
            //curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            break;
        case 'PUT':
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            //curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            break;
        case 'DELETE':
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            //curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            break;
        default:
            break;
    }
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
   
    
    $output = curl_exec($ch);   

    if(curl_exec($ch) === false)
    {
        echo 'Curl error: ' . curl_error($ch);
    }

    echo $output;

    curl_close($ch);