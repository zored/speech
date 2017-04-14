<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

// Autoload and add annotations:
AnnotationRegistry::registerLoader([
    require __DIR__ . '/vendor/autoload.php',
    'loadClass',
]);
