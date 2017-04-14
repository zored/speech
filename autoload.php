<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = require __DIR__ . '/vendor/autoload.php';

// Autoload and add annotations:
AnnotationRegistry::registerLoader([$loader, 'loadClass',]);
