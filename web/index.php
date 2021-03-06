<?php

use Zored\SpeechBundle\Endpoint\Context\ServiceContext;
use Zored\SpeechBundle\Endpoint\EndpointBuilder;
use Zored\SpeechBundle\Subscriber\UUID4Subscriber;
use Zored\SpeechBundle\Test\Functional\JsonRpc\Greeter;

// Composer class autoloader:
require __DIR__ . '/../autoload.php';

// Get JSON from GET or POST:
switch($_SERVER['REQUEST_METHOD']) {
    default:
    case 'GET':
        $json = $_GET['request'];
        break;

    case 'POST':
        $json = file_get_contents('php://input');
        break;
}

// JSON response:
header('Content-Type: application/json');

// Available services:
$context = new ServiceContext(['your.service']);

// Echo output:
echo (new EndpointBuilder())
//    ->addSubscriber(new UUID4Subscriber())
    ->addService('your.service', Greeter::class)
    ->getEndpoint()
    ->handle($json, $context);
