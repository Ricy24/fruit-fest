-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-10-2024 a las 05:18:06
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `fruitfest`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `campesinos`
--

CREATE TABLE `campesinos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `id_vendedor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `campesinos`
--

INSERT INTO `campesinos` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `tipo`, `imagen`, `id_vendedor`) VALUES
(3, 'Azada a la venta', 'poco uso', 25000.00, 'Herramientas', 'servicio', '../uploads/azada.png', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `id` int(11) NOT NULL,
  `id_usuarios` int(11) NOT NULL,
  `id_productos` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `precio` decimal(10,2) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciudades`
--

CREATE TABLE `ciudades` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `departamento_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ciudades`
--

INSERT INTO `ciudades` (`id`, `nombre`, `departamento_id`) VALUES
(1, 'Leticia', 1),
(2, 'Puerto Nariño', 1),
(3, 'Medellín', 2),
(4, 'Turbo', 2),
(5, 'Arauca', 3),
(6, 'Arauquita', 3),
(7, 'Barranquilla', 4),
(8, 'Soledad', 4),
(9, 'Cartagena', 5),
(10, 'Magangué', 5),
(11, 'Tunja', 6),
(12, 'Sogamoso', 6),
(13, 'Manizales', 7),
(14, 'Chinchiná', 7),
(15, 'Florencia', 8),
(16, 'San Vicente del Caguán', 8),
(17, 'Yopal', 9),
(18, 'Aguazul', 9),
(19, 'Popayán', 10),
(20, 'Cauca', 10),
(21, 'Valledupar', 11),
(22, 'La Jagua de Ibirico', 11),
(23, 'Quibdó', 12),
(24, 'Riosucio', 12),
(25, 'Montería', 13),
(26, 'Lorica', 13),
(27, 'Bogotá', 14),
(28, 'Soacha', 14),
(29, 'Inírida', 15),
(30, 'Puerto Inírida', 15),
(31, 'San José del Guaviare', 16),
(32, 'Calamar', 16),
(33, 'Riohacha', 17),
(34, 'Maicao', 17),
(35, 'Neiva', 18),
(36, 'Pitalito', 18),
(37, 'Santa Marta', 19),
(38, 'Ciénaga', 19),
(39, 'Villavicencio', 20),
(40, 'Acacías', 20),
(41, 'Pasto', 21),
(42, 'Tumaco', 21),
(43, 'Cúcuta', 22),
(44, 'Ocaña', 22),
(45, 'Mocoa', 23),
(46, 'Santiago', 23),
(47, 'Armenia', 24),
(48, 'Quimbaya', 24),
(49, 'Pereira', 25),
(50, 'Dosquebradas', 25),
(51, 'San Andrés', 26),
(52, 'Providencia', 26),
(53, 'Bucaramanga', 27),
(54, 'Floridablanca', 27),
(55, 'Sincelejo', 28),
(56, 'Sampués', 28),
(57, 'Ibagué', 29),
(58, 'Espinal', 29),
(59, 'Cali', 30),
(60, 'Palmira', 30),
(61, 'Mitú', 31),
(62, 'Vaupés', 31),
(63, 'Puerto Carreño', 32),
(64, 'Vichada', 32);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `codigos_promocionales`
--

CREATE TABLE `codigos_promocionales` (
  `id` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `descuento` decimal(5,2) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `codigos_promocionales`
--

INSERT INTO `codigos_promocionales` (`id`, `codigo`, `descuento`, `fecha_inicio`, `fecha_fin`) VALUES
(1, '2430', 30.00, '2024-09-15', '2024-09-30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `comentario` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comentarios`
--

INSERT INTO `comentarios` (`id`, `id_usuario`, `id_producto`, `comentario`, `created_at`, `parent_id`) VALUES
(2, 8, 1, 'Que delicia ', '2024-09-05 02:49:49', NULL),
(3, 8, 2, 'Muy buen producto', '2024-09-05 23:46:39', NULL),
(4, 9, 1, 'Vende por mas peso?\n', '2024-09-05 23:47:31', NULL),
(5, 9, 1, 'se ve muy rico\n', '2024-09-05 23:55:22', NULL),
(6, 6, 1, 'Si vendemos por mas peso.\r\n', '2024-09-06 00:00:52', 5),
(7, 6, 1, 'si muy rico', '2024-09-06 00:01:17', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contacto`
--

CREATE TABLE `contacto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_envio` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamentos`
--

CREATE TABLE `departamentos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `departamentos`
--

INSERT INTO `departamentos` (`id`, `nombre`) VALUES
(1, 'Amazonas'),
(2, 'Antioquia'),
(3, 'Arauca'),
(4, 'Atlántico'),
(5, 'Bolívar'),
(6, 'Boyacá'),
(7, 'Caldas'),
(8, 'Caquetá'),
(9, 'Casanare'),
(10, 'Cauca'),
(11, 'Cesar'),
(12, 'Chocó'),
(13, 'Córdoba'),
(14, 'Cundinamarca'),
(15, 'Guainía'),
(16, 'Guaviare'),
(17, 'Guajira'),
(18, 'Huila'),
(19, 'La Guajira'),
(20, 'Magdalena'),
(21, 'Meta'),
(22, 'Nariño'),
(23, 'Norte de Santander'),
(24, 'Putumayo'),
(25, 'Quindío'),
(26, 'Risaralda'),
(27, 'San Andrés y Providencia'),
(28, 'Santander'),
(29, 'Sucre'),
(30, 'Tolima'),
(31, 'Valle del Cauca'),
(32, 'Vaupés'),
(33, 'Vichada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_pedido`
--

CREATE TABLE `detalles_pedido` (
  `id` int(11) NOT NULL,
  `id_pedido` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `id_vendedor` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado_entrega` enum('pendiente','entregado') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_pedido`
--

INSERT INTO `detalles_pedido` (`id`, `id_pedido`, `id_producto`, `id_vendedor`, `cantidad`, `total`, `estado_entrega`) VALUES
(8, 7, 1, 6, 1, 3000.00, 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fotos`
--

CREATE TABLE `fotos` (
  `id` int(11) NOT NULL,
  `id_vendedor` int(11) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `fotos`
--

INSERT INTO `fotos` (`id`, `id_vendedor`, `url`, `descripcion`, `fecha`) VALUES
(1, 6, 'images.jpeg', '', '2024-09-06 01:33:04'),
(2, 6, '89233334_2821820391233780_5944512712242364416_n.jpg', 'si', '2024-09-06 01:57:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_productos`
--

CREATE TABLE `historial_productos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `fecha_visto` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_productos`
--

INSERT INTO `historial_productos` (`id`, `id_usuario`, `id_producto`, `fecha_visto`) VALUES
(4, 8, 1, '2024-09-05 20:58:20'),
(24, 8, 3, '2024-09-05 23:21:59'),
(29, 9, 1, '2024-09-05 23:47:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `reel_id` int(11) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_vendedor` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha` datetime NOT NULL,
  `leido` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `identificacion` varchar(50) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `metodo_pago` varchar(50) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `envio` decimal(10,2) NOT NULL,
  `descuento` decimal(10,2) NOT NULL,
  `total_pago` decimal(10,2) NOT NULL,
  `id_vendedor` int(11) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `leido` tinyint(1) DEFAULT 0,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `id_usuario`, `nombre`, `identificacion`, `direccion`, `telefono`, `metodo_pago`, `subtotal`, `envio`, `descuento`, `total_pago`, `id_vendedor`, `fecha`, `leido`, `email`) VALUES
(3, 8, 'andres', '24202845', 'Ciudad bolivar', '3239219404', 'tarjeta_credito', 2.50, 26000.00, 0.00, 26002.50, 6, '2024-09-04 22:12:02', 0, ''),
(7, 8, 'andres', '24202845', 'Ciudad bolivar', '3239219404', 'tarjeta_credito', 3000.00, 0.00, 0.00, 3000.00, 6, '2024-09-05 18:06:57', 0, ''),
(9, 8, 'andres', '24202845', 'Ciudad bolivar', '3239219404', 'tarjeta_credito', 2500.00, 0.00, 0.00, 2500.00, 6, '2024-09-05 18:18:09', 0, ''),
(10, 8, 'FRUIT FEST', '24202845', 'suba ', '3239219409', 'tarjeta_credito', 2500.00, 0.00, 750.00, 1750.00, 12, '2024-09-15 12:04:06', 0, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `popular_searches`
--

CREATE TABLE `popular_searches` (
  `id` int(11) NOT NULL,
  `search_query` varchar(255) NOT NULL,
  `search_count` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `popular_searches`
--

INSERT INTO `popular_searches` (`id`, `search_query`, `search_count`) VALUES
(1, 'Papa', 6),
(2, 'carne', 1),
(5, 'lechuga', 9),
(18, 'naranja', 2),
(22, 'promociones', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `num_calificaciones` int(11) DEFAULT 0,
  `calificacion_promedio` decimal(3,2) DEFAULT 0.00,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `categoria` varchar(1000) NOT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `destacado` tinyint(1) DEFAULT 0,
  `ventas` int(11) DEFAULT 0,
  `stock` int(11) DEFAULT 0,
  `id_vendedor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `num_calificaciones`, `calificacion_promedio`, `precio`, `imagen`, `categoria`, `is_featured`, `destacado`, `ventas`, `stock`, `id_vendedor`) VALUES
(1, 'alverja', 'Delicioso producto del campo a tu mesa\r\n\r\n', 0, 0.00, 3.00, '../uploads/0000001340.jpg', '0', 0, 0, 0, 10, 6),
(3, 'Lechuga', 'Fresca y cremosa', 0, 0.00, 5.00, '../uploads/images.jpeg', '0', 0, 0, 0, 200, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promociones`
--

CREATE TABLE `promociones` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `descuento` decimal(5,2) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recetas`
--

CREATE TABLE `recetas` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `ingredientes` text NOT NULL,
  `preparacion` text NOT NULL,
  `imagen_url` varchar(255) NOT NULL,
  `dificultad` varchar(50) NOT NULL,
  `cocinada_por` varchar(100) NOT NULL,
  `porciones` int(11) NOT NULL,
  `tiempo` varchar(50) NOT NULL,
  `fecha_agregado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recetas`
--

INSERT INTO `recetas` (`id`, `titulo`, `ingredientes`, `preparacion`, `imagen_url`, `dificultad`, `cocinada_por`, `porciones`, `tiempo`, `fecha_agregado`) VALUES
(1, 'Ensalada de Frutas Tropicales', '1 mango, 1 kiwi, 1 plátano, 1 taza de fresas, 1 taza de uvas, 1 limón (jugo), Hojas de menta (opcional)', '1. Pela y corta el mango, el kiwi y el plátano en trozos pequeños. 2. Lava las fresas y córtalas en mitades. 3. Corta las uvas por la mitad si son grandes. 4. Mezcla todas las frutas en un bol grande. 5. Exprime el jugo de limón sobre la mezcla de frutas y revuelve suavemente. 6. Decora con hojas de menta antes de servir.', 'https://cdn7.kiwilimon.com/recetaimagen/610/13211.jpg', 'Fácil', 'Chef Juan', 4, '15 minutos', '2024-07-28 20:23:10'),
(2, 'Smoothie Verde Energético', '1 taza de espinacas frescas, 1 manzana verde, 1 plátano, 1 kiwi, 1 taza de agua o leche vegetal, 1 cucharadita de miel (opcional)', '1. Lava bien las espinacas. 2. Pela y corta la manzana, el plátano y el kiwi en trozos. 3. Coloca todos los ingredientes en una licuadora. 4. Licúa hasta obtener una mezcla suave. 5. Añade miel si deseas más dulzura y mezcla nuevamente.', 'https://www.simpleblending.com/wp-content/uploads/2018/11/016A-Batido-verde-energizante_Simple-Blending.jpg', 'Fácil', 'Chef Ana', 2, '10 minutos', '2024-07-28 20:23:10'),
(3, 'Ensalada de Tomate y Aguacate', '2 tomates, 1 aguacate, 1 cebolla pequeña, Hojas de albahaca fresca, Aceite de oliva, Sal y pimienta al gusto', '1. Lava y corta los tomates en cubos. 2. Pela y corta el aguacate en cubos. 3. Pica finamente la cebolla. 4. Mezcla los tomates, aguacate y cebolla en un bol. 5. Agrega hojas de albahaca, aceite de oliva, sal y pimienta al gusto. 6. Revuelve suavemente y sirve.', 'https://7diasdesabor.com/wp-content/uploads/2022/03/avocado-salad.jpg', 'Fácil', 'Chef Luis', 4, '20 minutos', '2024-07-28 20:23:10'),
(4, 'Papas al Horno con Pimientos', '4 papas, 1 pimiento rojo, 1 pimiento verde, 2 cucharadas de aceite de oliva, Sal y pimienta al gusto, 1 cucharadita de romero seco', '1. Precalienta el horno a 200°C (390°F). 2. Lava y corta las papas en cubos. 3. Lava y corta los pimientos en tiras. 4. Mezcla las papas y los pimientos con aceite de oliva, sal, pimienta y romero. 5. Extiende la mezcla en una bandeja para hornear. 6. Hornea durante 30-40 minutos, o hasta que las papas estén doradas y crujientes.', 'https://www.palacios.es/palacios/usuariosFtp/conexion/imagenes4957a.jpg', 'Medio', 'Chef Carla', 4, '40 minutos', '2024-07-28 20:23:10'),
(5, 'Sopa de Brócoli y Cebolla', '1 brócoli, 1 cebolla, 2 dientes de ajo, 1 litro de caldo de verduras, 2 cucharadas de aceite de oliva, Sal y pimienta al gusto', '1. Lava y corta el brócoli en ramilletes. 2. Pela y pica finamente la cebolla y el ajo. 3. Calienta el aceite en una olla grande y sofríe la cebolla y el ajo hasta que estén dorados. 4. Agrega el brócoli y el caldo de verduras. 5. Cocina a fuego medio durante 15 minutos, o hasta que el brócoli esté tierno. 6. Usa una licuadora de mano para triturar la sopa hasta obtener una textura suave. 7. Ajusta la sal y la pimienta antes de servir.', 'https://www.lanacion.com.ar/resizer/v2/sopa-crema-de-HLZHLERRKFGV5CGYWNZWXMQRNA.jpg?auth=97a2b65b0f3bad7137da419c0c1046c52fba80567d87501bcf6bcc728abb3f81&width=420&height=280&quality=70&smart=true', 'Medio', 'Chef Mario', 4, '30 minutos', '2024-07-28 20:23:10'),
(6, 'Tacos de Calabacín y Pimientos', '2 calabacines, 2 pimientos (rojo y verde), 1 cebolla, Tortillas de maíz, Salsa de tu elección, Cilantro fresco (opcional)', '1. Lava y corta los calabacines y los pimientos en tiras. 2. Pela y corta la cebolla en tiras. 3. Calienta un poco de aceite en una sartén y cocina los calabacines, pimientos y cebolla hasta que estén tiernos. 4. Calienta las tortillas en una sartén o en el microondas. 5. Rellena las tortillas con la mezcla de verduras. 6. Añade salsa y cilantro fresco si lo deseas.', 'https://i.pinimg.com/736x/41/9c/a2/419ca2c1027b12de7af88e3b6bf2524a.jpg', 'Fácil', 'Chef Laura', 4, '20 minutos', '2024-07-28 20:23:10'),
(7, 'Ensalada de Manzana y Apio', '2 manzanas, 2 ramas de apio, 1/4 taza de nueces picadas, 1/4 taza de pasas, 2 cucharadas de mayonesa', '1. Lava y corta las manzanas en cubos. 2. Lava y corta el apio en trozos pequeños. 3. Mezcla las manzanas, el apio, las nueces y las pasas en un bol. 4. Añade la mayonesa y mezcla bien. 5. Sirve inmediatamente o refrigera hasta que esté listo para servir.', 'https://d36fw6y2wq3bat.cloudfront.net/recipes/ensalada-fria-de-manzana-y-apio/900/ensalada-fria-de-manzana-y-apio_version_1670299285.jpg', 'Fácil', 'Chef Pedro', 4, '15 minutos', '2024-07-28 20:23:10'),
(8, 'Smoothie de Sandía y Fresa', '2 tazas de sandía en cubos, 1 taza de fresas, 1 taza de agua de coco, 1 cucharada de miel (opcional)', '1. Pela y corta la sandía en cubos. 2. Lava las fresas y córtalas en mitades. 3. Coloca la sandía, las fresas y el agua de coco en una licuadora. 4. Licúa hasta obtener una mezcla suave. 5. Añade miel si prefieres más dulzura y mezcla nuevamente.', 'https://vitasweet.com.ec/wp-content/uploads/2022/05/smoothie_fresa_y_sandia.jpg', 'Fácil', 'Chef Sofia', 2, '10 minutos', '2024-07-28 20:23:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reels`
--

CREATE TABLE `reels` (
  `id` int(11) NOT NULL,
  `id_vendedor` int(11) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_subida` timestamp NOT NULL DEFAULT current_timestamp(),
  `me_gustas` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reels`
--

INSERT INTO `reels` (`id`, `id_vendedor`, `video_url`, `descripcion`, `fecha_subida`, `me_gustas`) VALUES
(8, 6, 'Download (1).mp4', 'depronto', '2024-09-21 22:24:33', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas`
--

CREATE TABLE `respuestas` (
  `id` int(11) NOT NULL,
  `id_mensaje` int(11) NOT NULL,
  `respuesta` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `search_history`
--

CREATE TABLE `search_history` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `search_query` varchar(255) DEFAULT NULL,
  `search_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguimientos`
--

CREATE TABLE `seguimientos` (
  `id` int(11) NOT NULL,
  `id_seguidor` int(11) DEFAULT NULL,
  `id_seguido` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `seguimientos`
--

INSERT INTO `seguimientos` (`id`, `id_seguidor`, `id_seguido`) VALUES
(38, 6, 6),
(40, 6, 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tips`
--

CREATE TABLE `tips` (
  `id` int(11) NOT NULL,
  `tip` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tips`
--

INSERT INTO `tips` (`id`, `tip`, `created_at`) VALUES
(1, 'Recuerda actualizar tus productos regularmente.', '2024-09-05 23:39:45'),
(2, 'Ofrece descuentos especiales para atraer más clientes.', '2024-09-05 23:39:45'),
(3, 'Mantén un buen stock para evitar la falta de productos.', '2024-09-05 23:39:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`) VALUES
(8, 'Andres Duran', 'discordandres20@gmail.com', '$2y$10$mTxjaeczkUQzoxYKhSV6ceGeQyzNVCuMtb7UfSnxDKKvWYnrF7gXm'),
(9, 'Andres ricardo Duran corredor', 'anriduco2019@gmail.com', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vendedores`
--

CREATE TABLE `vendedores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `cedula` varchar(20) DEFAULT NULL,
  `ciudad` varchar(255) DEFAULT NULL,
  `departamento` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `verificado` tinyint(1) DEFAULT 0,
  `verification_code` varchar(6) DEFAULT NULL,
  `security_answer` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `last_failed_attempt` datetime DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vendedores`
--

INSERT INTO `vendedores` (`id`, `nombre`, `email`, `telefono`, `direccion`, `foto`, `password`, `fecha_registro`, `cedula`, `ciudad`, `departamento`, `token`, `verificado`, `verification_code`, `security_answer`, `token_expiry`, `ip_address`, `last_failed_attempt`, `facebook`, `twitter`, `instagram`) VALUES
(6, 'andres', 'discordandres20@gmail.com', '+573239219404', 'kr 71-58-50 sur', '4de7959842ee3a3ae1f9e9d16b050364.jpg', '$2y$10$tN8UhyB90acN4Dzswfa4DuQrpRv8ArvDP4saiW2tRlar1FB76JN.i', '2024-08-26 00:37:57', '24202845', 'Bogota ', 'Cundinamarca', '', 1, NULL, NULL, NULL, '::1', NULL, '', '', ''),
(12, 'Yamile', 'yamilecorredor60@gmail.com', '3134317620', 'Kr 71-58-50 sur', '89233334_2821820391233780_5944512712242364416_n.jpg', '$2y$10$c4A2pqmq4mbz16RGO2806.r9cDt7cRxpssgvVt3DujIfnKxgK.QW6', '2024-09-06 01:19:52', '1049616429', 'Tunja', 'Cundinamarca', '', 1, NULL, NULL, NULL, '::1', NULL, NULL, NULL, NULL),
(16, 'FRUIT FEST', 'fruitfest.co@gmail.com', '3239219409', 'Paipa', '459268946_122106044216515081_4783691548439395718_n.jpg', '$2y$10$vhy5fSsZMZZbRL2Ias4HVeD5zMLVEvtVa3IujaEC0F7pe.uGCdj0.', '2024-09-15 16:35:35', '24202847', '12', '6', '', 1, NULL, NULL, NULL, '::1', NULL, '', NULL, ''),
(17, 'Riki', 'anriduco2019@gmail.com', '3123079738', 'kr 74', 'images.jfif', '$2y$10$37Yq3H.XOg2c0pq63sOImujzG.ZMBzWJWqCOLKajokG/QR9pSDWua', '2024-10-02 00:31:58', '7413075800', '27', '14', '61e60e624f77387a521b27453b52adbf', 0, '95d6b7', NULL, NULL, '::1', NULL, '', NULL, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `verification_codes`
--

CREATE TABLE `verification_codes` (
  `id` int(11) NOT NULL,
  `id_vendedor` int(11) DEFAULT NULL,
  `code` varchar(6) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `campesinos`
--
ALTER TABLE `campesinos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuarios` (`id_usuarios`),
  ADD KEY `id_productos` (`id_productos`);

--
-- Indices de la tabla `ciudades`
--
ALTER TABLE `ciudades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `departamento_id` (`departamento_id`);

--
-- Indices de la tabla `codigos_promocionales`
--
ALTER TABLE `codigos_promocionales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `idx_parent_id` (`parent_id`);

--
-- Indices de la tabla `contacto`
--
ALTER TABLE `contacto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `fotos`
--
ALTER TABLE `fotos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_vendedor` (`id_vendedor`);

--
-- Indices de la tabla `historial_productos`
--
ALTER TABLE `historial_productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_usuario_producto` (`id_usuario`,`id_producto`),
  ADD UNIQUE KEY `unique_user_product` (`id_usuario`,`id_producto`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `reel_id` (`reel_id`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_vendedor` (`id_vendedor`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `popular_searches`
--
ALTER TABLE `popular_searches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `search_query` (`search_query`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `promociones`
--
ALTER TABLE `promociones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `recetas`
--
ALTER TABLE `recetas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reels`
--
ALTER TABLE `reels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendedor_id` (`id_vendedor`);

--
-- Indices de la tabla `respuestas`
--
ALTER TABLE `respuestas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_mensaje` (`id_mensaje`);

--
-- Indices de la tabla `search_history`
--
ALTER TABLE `search_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `seguimientos`
--
ALTER TABLE `seguimientos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_seguidor` (`id_seguidor`,`id_seguido`),
  ADD KEY `id_seguido` (`id_seguido`);

--
-- Indices de la tabla `tips`
--
ALTER TABLE `tips`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `vendedores`
--
ALTER TABLE `vendedores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `cedula` (`cedula`);

--
-- Indices de la tabla `verification_codes`
--
ALTER TABLE `verification_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_vendedor` (`id_vendedor`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `campesinos`
--
ALTER TABLE `campesinos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ciudades`
--
ALTER TABLE `ciudades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT de la tabla `codigos_promocionales`
--
ALTER TABLE `codigos_promocionales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `contacto`
--
ALTER TABLE `contacto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `fotos`
--
ALTER TABLE `fotos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `historial_productos`
--
ALTER TABLE `historial_productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT de la tabla `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `popular_searches`
--
ALTER TABLE `popular_searches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `recetas`
--
ALTER TABLE `recetas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `reels`
--
ALTER TABLE `reels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `seguimientos`
--
ALTER TABLE `seguimientos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de la tabla `tips`
--
ALTER TABLE `tips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `vendedores`
--
ALTER TABLE `vendedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `verification_codes`
--
ALTER TABLE `verification_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ciudades`
--
ALTER TABLE `ciudades`
  ADD CONSTRAINT `ciudades_ibfk_1` FOREIGN KEY (`departamento_id`) REFERENCES `departamentos` (`id`);

--
-- Filtros para la tabla `fotos`
--
ALTER TABLE `fotos`
  ADD CONSTRAINT `fotos_ibfk_1` FOREIGN KEY (`id_vendedor`) REFERENCES `vendedores` (`id`);

--
-- Filtros para la tabla `historial_productos`
--
ALTER TABLE `historial_productos`
  ADD CONSTRAINT `historial_productos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `historial_productos_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`reel_id`) REFERENCES `reels` (`id`);

--
-- Filtros para la tabla `reels`
--
ALTER TABLE `reels`
  ADD CONSTRAINT `reels_ibfk_1` FOREIGN KEY (`id_vendedor`) REFERENCES `vendedores` (`id`);

--
-- Filtros para la tabla `seguimientos`
--
ALTER TABLE `seguimientos`
  ADD CONSTRAINT `seguimientos_ibfk_1` FOREIGN KEY (`id_seguidor`) REFERENCES `vendedores` (`id`),
  ADD CONSTRAINT `seguimientos_ibfk_2` FOREIGN KEY (`id_seguido`) REFERENCES `vendedores` (`id`);

--
-- Filtros para la tabla `verification_codes`
--
ALTER TABLE `verification_codes`
  ADD CONSTRAINT `verification_codes_ibfk_1` FOREIGN KEY (`id_vendedor`) REFERENCES `vendedores` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
