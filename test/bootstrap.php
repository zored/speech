<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

// Standalone or inside of project:
$path = file_exists(__DIR__ . '/../../../../../../vendor/autoload.php')
    ? __DIR__ . '/../../../../../../vendor/autoload.php'
    : __DIR__ . '/../autoload.php';

require $path;

AnnotationRegistry::registerLoader('class_exists');
