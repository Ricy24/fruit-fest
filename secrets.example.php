<?php
/**
 * PLANTILLA de secretos de Fruit Fest.
 *
 * 1. Copia este archivo:
 *      copy secrets.example.php secrets.php      (Windows)
 *      cp secrets.example.php secrets.php        (macOS/Linux)
 * 2. Rellena con tus valores reales.
 *
 * `secrets.php` está en .gitignore: NUNCA se sube al repositorio.
 *
 * @return array
 */
return [
    // Google OAuth -> Google Cloud Console > APIs y servicios > Credenciales
    'google_client_id'     => 'xxxxxxxxxxxxxxxx.apps.googleusercontent.com',
    'google_client_secret' => 'GOCSPX-xxxxxxxxxxxxxxxx',

    // Contraseña SMTP de cada buzón (PHPMailer)
    'smtp' => [
        'fruitfestautomatico@outlook.com'     => '',
        'frutasverdurasfruitfest@outlook.com' => '',
    ],

    // OpenWeatherMap -> https://openweathermap.org/api
    'openweathermap_api_key' => '',
];
