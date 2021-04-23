<?php

$base = require __DIR__ . '/../../../vendor/laravel-lang/lang/source/validation.php';
return array_merge($base, [
    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],
    'attributes' => [

    ],
]);
