<?php

require __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL);

$whoops = new \Whoops\Run();
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
$whoops->register();

$psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();

$creator = new \Nyholm\Psr7Server\ServerRequestCreator(
    $psr17Factory, // ServerRequestFactory
    $psr17Factory, // UriFactory
    $psr17Factory, // UploadedFileFactory
    $psr17Factory  // StreamFactory
);

$serverRequest = $creator->fromGlobals();

$path = $serverRequest->getUri()->getPath();

use Psr\Http\Server\RequestHandlerInterface;

if ($path === '/now') {
    // $response = $psr17Factory->createResponse(200)
    //     ->withBody(
    //         $psr17Factory->createStream(date('Y年m月d日 H時i分s秒'))
    //     );
    $handler = new \yorumori\MyPsr\Http\Handler\DateAction();
    $response = $handler->handle($serverRequest);
} else {
    $response = $psr17Factory->createResponse(404)
        ->withBody(
            $psr17Factory->createStream('そのページはありません')
        );
}

(new \Laminas\HttpHandlerRunner\Emitter\SapiEmitter())->emit($response);

// echo (string)$response->getBody();
