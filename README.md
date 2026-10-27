# 🍓 Fruit Fest

<div align="center">

![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat-square&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat-square&logo=javascript&logoColor=black)
![Google OAuth](https://img.shields.io/badge/Google-OAuth%202.0-4285F4?style=flat-square&logo=google&logoColor=white)
![SENA](https://img.shields.io/badge/Reconocido-Concurso%20SENA%20Bogot%C3%A1-CEBE2E?style=flat-square)

</div>

> Tienda en línea de frutas y verduras con **carrito, pagos, portal de vendedores, panel de administración, facturación en PDF y recomendaciones inteligentes**. Proyecto reconocido en el concurso SENA Bogotá.

---

## 📋 Descripción

**Fruit Fest** es un e-commerce agrícola completo desarrollado en PHP. Los clientes pueden explorar el catálogo, comprar con carrito y pasarela de pago, ver su historial, leer recetas y chatear con soporte. Los **vendedores** gestionan sus propios productos y pedidos desde un portal dedicado, y un **panel de administración** supervisa toda la operación.

Incluye autenticación con **Google**, verificación de cuenta por **SMS/código**, envío de correos transaccionales y generación de documentos en **PDF**.

## ✨ Características

**🛒 Tienda (cliente)**
- Catálogo de productos con buscador y detalle
- Carrito de compras persistente (`add_to_cart`, `actualizar_carrito`, `ver_carrito`)
- Proceso de pago y confirmación (`pago.php`, `procesar_pago.php`, `confirmacion_pago.php`)
- Historial de pedidos, recetas, chat de soporte y área personal
- Inicio de sesión con **Google OAuth 2.0**
- Documentos generados en **PDF** con FPDF (`generar_pdf.php`)

**🧑‍🌾 Portal de vendedores** (`vendedores/`)
- Alta, edición y eliminación de productos con fotos
- Gestión de pedidos y estados
- Recuperación de contraseña y de PIN por código de verificación
- **Recomendaciones con el pronóstico del tiempo** de la ciudad del vendedor (OpenWeatherMap)
- Certificados de vendedor

**🛠️ Administración** (`panel/`)
- Panel de administración con métricas y gestión general
- Base de datos incluida (`database/fruitfest.sql`)

## 🛠️ Stack

`PHP` · `MySQL` · `JavaScript` · `CSS`

**Dependencias (Composer):** `phpmailer/phpmailer` · `google/apiclient` · `twilio/sdk` · `laravel/framework`

## 🚀 Instalación

### Requisitos

- [XAMPP](https://www.apachefriends.org/) (o WAMP/LAMP) con **PHP 8+** y **MySQL**
- [Composer](https://getcomposer.org/)

### Pasos

1. **Clonar** el repositorio dentro de `htdocs`:

```bash
cd C:/xampp/htdocs
git clone https://github.com/Ricy24/fruit-fest.git
cd fruit-fest
```

2. **Instalar dependencias:**

```bash
composer install
```

3. **Crear la base de datos** en `http://localhost/phpmyadmin`:
   - Crea una base de datos llamada `fruitfest`
   - Importa [`database/fruitfest.sql`](database/fruitfest.sql)

4. **Configurar la conexión** (valores por defecto de XAMPP: `root` sin contraseña):

   - [`config.php`](config.php)
   - [`conexion.php`](conexion.php)
   - [`database.php`](database.php)
   - [`config/database.php`](config/database.php)

5. **Configurar los secretos** (obligatorio):

```bash
copy secrets.example.php secrets.php
```

   Edita `secrets.php` y coloca tus credenciales reales:

   | Clave | Descripción |
   |---|---|
   | `google_client_id` / `google_client_secret` | Credenciales de OAuth en [Google Cloud Console](https://console.cloud.google.com/apis/credentials) |
   | `smtp.*` | Contraseñas de los buzones que usa PHPMailer |
   | `openweathermap_api_key` | API key de [OpenWeatherMap](https://openweathermap.org/api) |

6. **Ejecutar:** abre `http://localhost/fruit-fest/`

> ⚠️ Registra en Google Cloud Console el *Authorized redirect URI*: `http://localhost/prueba/google-callback.php` (o el que uses en tu entorno).

## 📁 Estructura

```text
fruit-fest/
├── index.php                 # Portada con carrusel y destacados
├── productos.php             # Catálogo
├── producto_detalle.php      # Detalle de producto
├── add_to_cart.php           # Agregar al carrito
├── ver_carrito.php           # Ver carrito
├── pago.php                  # Pasarela de pago
├── confirmacion_pago.php     # Confirmación y correo de compra
├── historial_pedidos.php     # Historial del cliente
├── recetas.php · chat.php    # Contenido y soporte
├── register.php / login.php  # Autenticación
├── google-callback.php       # Callback de Google OAuth
├── generar_pdf.php           # Documentos en PDF (FPDF)
│
├── panel/                    # Panel de administración
├── vendedores/               # Portal del vendedor (productos, pedidos, tips)
├── procesos/                 # Lógica de autenticación y procesamiento
│
├── database/fruitfest.sql    # Esquema y datos
├── config · conexion.php     # Conexiones a MySQL
├── secrets.example.php       # Plantilla de secretos
├── secrets_loader.php        # Cargador de secretos (versionado)
├── secrets.php               # ⚠️ Valores reales (NO se sube, va en .gitignore)
└── vendor/                   # Dependencias de Composer
```

## 🔐 Seguridad

- Los secretos **nunca** se suben al repositorio: viven en `secrets.php`, ignorado por Git.
- Cualquier credencial expuesta anteriormente debe **rotarse** (contraseñas SMTP, client secret de Google, API key de OpenWeatherMap).

## 📄 Licencia

Proyecto desarrollado con **fines académicos** — Concurso SENA Bogotá.
