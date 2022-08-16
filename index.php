<?php 

require __DIR__ . '/vendor/autoload.php'; 

use Proxy\Proxy;
use Proxy\Adapter\Guzzle\GuzzleAdapter;
use Proxy\Filter\RemoveEncodingFilter;
use Laminas\Diactoros\ServerRequestFactory;

// Create a PSR7 request based on the current browser request.
$request = ServerRequestFactory::fromGlobals();

// Create a guzzle client
$guzzle = new GuzzleHttp\Client();

// Create the proxy instance
$proxy = new Proxy(new GuzzleAdapter($guzzle));

// Add a response filter that removes the encoding headers.
$proxy->filter(new RemoveEncodingFilter());

try {
    // Forward the request and get the response.
    $response = $proxy->forward($request)->to('https://reqbin.com/echo/post/xml');

    // Output response to the browser.
    (new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);
} catch(\GuzzleHttp\Exception\BadResponseException $e) {
    // Correct way to handle bad responses
    (new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($e->getResponse());
}