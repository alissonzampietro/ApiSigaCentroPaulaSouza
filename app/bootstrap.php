<?php

use Respect\Rest\Router;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;

use SigaApp\Faltas\Controller\FaltasController;

$encoders = array(new JsonEncoder(), new XmlEncoder());
$normalizers = array(new ObjectNormalizer(), new PropertyNormalizer());
$serializer = new Serializer($normalizers, $encoders);

$router = new Router();

$router->always('Accept', array(
    'text/html' => function($input) {
    header('HTTP/1.1 400 Bad Request');
    return "<h1>Error 404 Bad Request</h1>";
    },
    'application/json' => function($input) use ($serializer) {
    return $serializer->serialize($input, 'json');
    },
    'text/xml' => function($input) use ($serializer) {
    return $serializer->serialize($input, 'xml');
    }
    ));

$router->any("/faltas", "SigaApp\Faltas\Controller\FaltasController");