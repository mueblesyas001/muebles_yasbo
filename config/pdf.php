<?php

return [
    'driver' => 'dompdf', 
    
    'drivers' => [
        'dompdf' => [
            'driver' => \Spatie\LaravelPdf\Drivers\Dompdf::class,
        ],
        'browsershot' => [
            'driver' => \Spatie\LaravelPdf\Drivers\Browsershot::class,
        ],
    ],
];