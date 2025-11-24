<?php
# Modelo base de tienda - Clonado al crear nueva tienda

return [
    'name' => 'Nueva Tienda',
    'description' => 'Bienvenido a tu tienda. Edita esta descripción.',
    'theme' => 'default',
    'logo_url' => '/assets/logos/default-store.png',
    'settings' => [
        'currency' => 'USD',
        'timezone' => 'America/Havana',
        'inventory_enabled' => true,
        'analytics_enabled' => true,
    ],
    'sample_products' => [
        [
            'title' => 'Producto Ejemplo',
            'price' => 100,
            'stock' => 20,
            'image_url' => '/assets/sample/sample-product.jpg'
        ]
    ]
];store
