<?php
/**
 * Cargador de secretos de Fruit Fest.
 *
 * Este archivo SÍ se versiona; los valores reales viven en `secrets.php`,
 * que está en .gitignore y nunca se sube al repositorio.
 *
 * Uso:
 *   sf_secret('google_client_secret')
 *   sf_secret('smtp.fruitfestautomatico@outlook.com')
 *   sf_secret('openweathermap_api_key')
 */

function sf_secret(string $key, string $default = ''): string
{
    static $secrets = null;

    if ($secrets === null) {
        $file = __DIR__ . '/secrets.php';
        $secrets = (is_file($file) ? require $file : []);
        if (!is_array($secrets)) {
            $secrets = [];
        }
    }

    $value = $secrets;
    foreach (explode('.', $key) as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }

    return is_scalar($value) ? (string) $value : $default;
}
