-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-10-2025 a las 15:51:13
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
-- Base de datos: `portal_noticias`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `audit_log`
--

CREATE TABLE `audit_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `entity` varchar(80) NOT NULL,
  `entity_id` int(10) UNSIGNED DEFAULT NULL,
  `action` varchar(60) NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `businesses`
--

CREATE TABLE `businesses` (
  `id` int(10) UNSIGNED NOT NULL,
  `section_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL,
  `slug` varchar(230) NOT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `advertisement_text` text DEFAULT NULL,
  `mission` text DEFAULT NULL,
  `vision` text DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `phone` varchar(60) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=Activo, 2=Inactivo',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `is_open` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Si el negocio está abierto (1) o cerrado (0)',
  `closed_reason` varchar(255) DEFAULT NULL COMMENT 'Razón por la cual está cerrado si is_open = 0',
  `business_hours` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Horarios de atención del negocio en formato JSON' CHECK (json_valid(`business_hours`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `businesses`
--

INSERT INTO `businesses` (`id`, `section_id`, `name`, `slug`, `short_description`, `description`, `advertisement_text`, `mission`, `vision`, `logo_path`, `website`, `phone`, `address`, `is_published`, `status`, `created_by`, `created_at`, `updated_at`, `is_open`, `closed_reason`, `business_hours`) VALUES
(1, 1, 'Pizza Express', 'pizza-express', 'Las mejores pizzas artesanales de la ciudad', 'Pizza Express es un restaurante especializado en pizzas artesanales hechas con ingredientes frescos y masa tradicional. Ofrecemos una amplia variedad de sabores para todos los gustos.', NULL, 'Ofrecer las mejores pizzas artesanales con ingredientes frescos y de calidad.', 'Ser la pizzería favorita de las familias bogotanas.', NULL, 'https://pizzaexpress.com', '301-234-5678', 'Calle 123 #45-67, Bogotá', 1, 1, 3, '2025-10-02 14:43:43', '2025-10-02 15:20:59', 1, NULL, '{\"monday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"tuesday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"wednesday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"thursday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"friday\": {\"open\": \"08:00\", \"close\": \"23:00\", \"is_open\": true}, \"saturday\": {\"open\": \"09:00\", \"close\": \"23:00\", \"is_open\": true}, \"sunday\": {\"open\": \"10:00\", \"close\": \"21:00\", \"is_open\": true}}'),
(2, 1, 'Burger Mania', 'burger-mania', 'Hamburguesas gourmet y papas artesanales', 'Burger Mania se especializa en hamburguesas gourmet preparadas con carne 100% res, pan artesanal y los mejores acompañamientos. También ofrecemos opciones vegetarianas.', NULL, 'Crear las mejores hamburguesas gourmet con ingredientes premium.', 'Revolucionar el concepto de comida rápida con calidad gourmet.', NULL, 'https://burgermania.com', '302-345-6789', 'Carrera 7 #89-12, Bogotá', 1, 1, 4, '2025-10-02 14:43:43', '2025-10-02 15:20:59', 0, 'Renovaciones en el local', '{\"monday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"tuesday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"wednesday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"thursday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"friday\": {\"open\": \"08:00\", \"close\": \"23:00\", \"is_open\": true}, \"saturday\": {\"open\": \"09:00\", \"close\": \"23:00\", \"is_open\": true}, \"sunday\": {\"open\": \"10:00\", \"close\": \"21:00\", \"is_open\": true}}'),
(3, 1, 'Pollo Dorado', 'pollo-dorado', 'Pollo asado y broaster con sazón casero', 'Pollo Dorado ofrece pollo asado y broaster con receta tradicional familiar. Servimos porciones generosas con guarniciones caseras y salsas especiales.', NULL, 'Mantener el sabor tradicional del pollo casero con la mejor calidad.', 'Ser reconocidos como el mejor pollo de la zona.', NULL, NULL, '303-456-7890', 'Avenida 68 #34-56, Bogotá', 1, 1, 3, '2025-10-02 14:43:43', '2025-10-02 15:20:59', 1, NULL, '{\"monday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"tuesday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"wednesday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"thursday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"friday\": {\"open\": \"08:00\", \"close\": \"23:00\", \"is_open\": true}, \"saturday\": {\"open\": \"09:00\", \"close\": \"23:00\", \"is_open\": true}, \"sunday\": {\"open\": \"10:00\", \"close\": \"21:00\", \"is_open\": true}}'),
(4, 2, 'Restaurante Bogotá', 'restaurante-bogota', 'Comida típica colombiana en ambiente familiar', 'Restaurante Bogotá es un acogedor lugar donde servimos los platos más tradicionales de la cocina colombiana. Bandeja paisa, sancocho, ajiaco y muchos más platos típicos preparados con amor.', NULL, 'Preservar y compartir la tradición culinaria colombiana.', 'Ser el referente de la comida típica colombiana en Bogotá.', NULL, 'https://restaurantebogota.com', '304-567-8901', 'Calle 72 #11-23, Bogotá', 1, 1, 6, '2025-10-02 14:43:43', '2025-10-02 15:20:59', 1, NULL, '{\"monday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"tuesday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"wednesday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"thursday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"friday\": {\"open\": \"08:00\", \"close\": \"23:00\", \"is_open\": true}, \"saturday\": {\"open\": \"09:00\", \"close\": \"23:00\", \"is_open\": true}, \"sunday\": {\"open\": \"10:00\", \"close\": \"21:00\", \"is_open\": true}}'),
(5, 2, 'La Hacienda', 'la-hacienda', 'Parrilla y carnes a la brasa', 'La Hacienda es especialista en carnes a la parrilla y cortes premium. Ofrecemos un ambiente campestre con la mejor atención para disfrutar en familia o con amigos.', NULL, 'Ofrecer las mejores carnes con preparación tradicional a la parrilla.', 'Ser el lugar preferido para celebrar momentos especiales.', NULL, NULL, '305-678-9012', 'Calle 85 #15-30, Bogotá', 1, 1, 4, '2025-10-02 14:43:43', '2025-10-02 15:20:59', 1, NULL, '{\"monday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"tuesday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"wednesday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"thursday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"friday\": {\"open\": \"08:00\", \"close\": \"23:00\", \"is_open\": true}, \"saturday\": {\"open\": \"09:00\", \"close\": \"23:00\", \"is_open\": true}, \"sunday\": {\"open\": \"10:00\", \"close\": \"21:00\", \"is_open\": true}}'),
(6, 2, 'Sushi Zen', 'sushi-zen', 'Sushi fresco y cocina japonesa auténtica', 'Sushi Zen trae lo mejor de la cocina japonesa a Bogotá. Nuestros chefs especializados preparan sushi fresco daily y platos japoneses tradicionales con ingredientes importados.', NULL, 'Ofrecer una experiencia auténtica de la cultura gastronómica japonesa.', 'Ser el restaurante japonés más reconocido de la ciudad.', NULL, 'https://sushizen.com', '306-789-0123', 'Zona Rosa, Calle 82 #12-45, Bogotá', 1, 1, 6, '2025-10-02 14:43:43', '2025-10-02 15:20:59', 1, NULL, '{\"monday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"tuesday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"wednesday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"thursday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"friday\": {\"open\": \"08:00\", \"close\": \"23:00\", \"is_open\": true}, \"saturday\": {\"open\": \"09:00\", \"close\": \"23:00\", \"is_open\": true}, \"sunday\": {\"open\": \"10:00\", \"close\": \"21:00\", \"is_open\": true}}'),
(7, 3, 'Café Central', 'cafe-central', 'Café de especialidad y repostería artesanal', 'Café Central es un acogedor espacio donde servimos café de especialidad colombiano y repostería artesanal. Perfecto para reuniones de trabajo, estudio o relajarse.', NULL, 'Promover el café colombiano de alta calidad en un ambiente acogedor.', 'Ser el punto de encuentro preferido para los amantes del café.', NULL, 'https://cafecentral.com', '307-890-1234', 'Avenida 19 #103-45, Bogotá', 1, 1, 5, '2025-10-02 14:43:43', '2025-10-02 15:20:59', 1, NULL, '{\"monday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"tuesday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"wednesday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"thursday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"friday\": {\"open\": \"08:00\", \"close\": \"23:00\", \"is_open\": true}, \"saturday\": {\"open\": \"09:00\", \"close\": \"23:00\", \"is_open\": true}, \"sunday\": {\"open\": \"10:00\", \"close\": \"21:00\", \"is_open\": true}}'),
(8, 3, 'Sweet Dreams', 'sweet-dreams', 'Heladería artesanal y postres', 'Sweet Dreams ofrece helados artesanales con sabores únicos y postres caseros. Todos nuestros productos son elaborados diariamente con ingredientes naturales.', NULL, 'Endulzar los momentos especiales con productos artesanales de calidad.', 'Ser la heladería favorita de toda la familia.', NULL, NULL, '308-901-2345', 'Centro Comercial Andino, Local 234', 1, 1, 5, '2025-10-02 14:43:43', '2025-10-02 15:20:59', 1, NULL, '{\"monday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"tuesday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"wednesday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"thursday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"friday\": {\"open\": \"08:00\", \"close\": \"23:00\", \"is_open\": true}, \"saturday\": {\"open\": \"09:00\", \"close\": \"23:00\", \"is_open\": true}, \"sunday\": {\"open\": \"10:00\", \"close\": \"21:00\", \"is_open\": true}}'),
(9, 4, 'Pan Casero', 'pan-casero', 'Panadería tradicional con productos frescos', 'Pan Casero es una panadería familiar que elabora pan fresco todos los días. Ofrecemos productos tradicionales y especialidades de panadería y pastelería.', NULL, 'Mantener la tradición panadera con productos frescos y naturales.', 'Ser la panadería de confianza del barrio.', NULL, NULL, '309-012-3456', 'Carrera 15 #67-89, Bogotá', 1, 1, 3, '2025-10-02 14:43:43', '2025-10-02 15:20:59', 1, NULL, '{\"monday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"tuesday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"wednesday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"thursday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"friday\": {\"open\": \"08:00\", \"close\": \"23:00\", \"is_open\": true}, \"saturday\": {\"open\": \"09:00\", \"close\": \"23:00\", \"is_open\": true}, \"sunday\": {\"open\": \"10:00\", \"close\": \"21:00\", \"is_open\": true}}'),
(10, 4, 'Dulce Tentación', 'dulce-tentacion', 'Repostería fina y tortas personalizadas', 'Dulce Tentación se especializa en repostería fina y tortas personalizadas para eventos especiales. Creamos dulces momentos con productos artesanales únicos.', NULL, 'Crear productos de repostería únicos para momentos especiales.', 'Ser reconocidos por la excelencia en repostería artesanal.', NULL, 'https://dulcetentacion.com', '310-123-4567', 'Calle 127 #9-34, Bogotá', 1, 1, 6, '2025-10-02 14:43:43', '2025-10-02 15:20:59', 1, NULL, '{\"monday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"tuesday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"wednesday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"thursday\": {\"open\": \"08:00\", \"close\": \"22:00\", \"is_open\": true}, \"friday\": {\"open\": \"08:00\", \"close\": \"23:00\", \"is_open\": true}, \"saturday\": {\"open\": \"09:00\", \"close\": \"23:00\", \"is_open\": true}, \"sunday\": {\"open\": \"10:00\", \"close\": \"21:00\", \"is_open\": true}}');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `business_images`
--

CREATE TABLE `business_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `uploaded_by` int(10) UNSIGNED DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `business_videos`
--

CREATE TABLE `business_videos` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `video_type` enum('youtube','vimeo','upload') NOT NULL DEFAULT 'youtube',
  `video_url` varchar(500) DEFAULT NULL,
  `video_path` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `uploaded_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `business_videos`
--

INSERT INTO `business_videos` (`id`, `business_id`, `video_type`, `video_url`, `video_path`, `title`, `description`, `thumbnail_path`, `display_order`, `is_active`, `uploaded_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'youtube', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', NULL, 'Video promocional de Pizza Express', 'Conoce nuestras deliciosas pizzas', NULL, 0, 1, NULL, '2025-10-02 16:04:53', NULL),
(2, 2, 'youtube', 'https://www.youtube.com/watch?v=oHg5SJYRHA0', NULL, 'Las mejores hamburguesas de la ciudad', 'Tour por nuestra cocina', NULL, 0, 1, NULL, '2025-10-02 16:04:53', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `news`
--

CREATE TABLE `news` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(300) NOT NULL,
  `summary` varchar(500) DEFAULT NULL,
  `content` longtext NOT NULL,
  `published_at` datetime DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `author_id` int(10) UNSIGNED DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `views` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `news`
--

INSERT INTO `news` (`id`, `title`, `slug`, `summary`, `content`, `published_at`, `is_published`, `author_id`, `featured_image`, `views`, `created_at`, `updated_at`) VALUES
(1, 'Nuevo restaurante japonés abre sus puertas en la Zona Rosa', 'nuevo-restaurante-japones-zona-rosa', 'Sushi Zen trae auténtica cocina japonesa a Bogotá con chefs especializados y ingredientes importados.', 'La Zona Rosa de Bogotá recibe un nuevo concepto gastronómico con la apertura de Sushi Zen, un restaurante que promete traer la auténtica experiencia de la cocina japonesa a la capital colombiana.\n\nEl restaurante, ubicado en la Calle 82 #12-45, cuenta con chefs especializados que han sido entrenados en Japón y utiliza ingredientes frescos importados directamente desde el país asiático.\n\n\"Queremos ofrecer una experiencia auténtica de la cultura gastronómica japonesa\", comentó el chef principal durante la inauguración.\n\nEl menú incluye una amplia variedad de sushi, sashimi, ramen y platos tradicionales japoneses, todos preparados siguiendo técnicas milenarias.\n\nSushi Zen estará abierto de lunes a domingo de 12:00 PM a 10:00 PM y ya está recibiendo reservas para grupos y eventos especiales.', '2024-10-01 10:00:00', 1, 2, NULL, 45, '2025-10-02 14:43:43', NULL),
(2, 'Pizza Express lanza servicio de delivery 24 horas', 'pizza-express-delivery-24-horas', 'La popular pizzería amplía su horario de servicio para satisfacer la demanda nocturna de sus clientes.', 'Pizza Express, reconocida por sus pizzas artesanales, anuncia el lanzamiento de su nuevo servicio de delivery 24 horas, convirtiéndose en la primera pizzería de la zona en ofrecer este beneficio.\n\nLa decisión surge después de múltiples solicitudes de clientes que deseaban disfrutar de sus pizzas favoritas durante horarios nocturnos y madrugada.\n\n\"Escuchamos a nuestros clientes y decidimos ampliar nuestro servicio para estar disponibles cuando nos necesiten\", expresó Carlos Rodríguez, propietario del establecimiento.\n\nEl servicio estará disponible en un radio de 10 kilómetros desde su ubicación en la Calle 123 #45-67, con un tiempo promedio de entrega de 30 minutos.\n\nAdemás del servicio extendido, Pizza Express mantiene su compromiso con ingredientes frescos y masa preparada diariamente.', '2024-09-30 15:30:00', 1, 2, NULL, 67, '2025-10-02 14:43:43', NULL),
(3, 'Café Central gana premio al mejor café de especialidad', 'cafe-central-premio-mejor-cafe', 'El establecimiento fue reconocido por su compromiso con el café colombiano de alta calidad y su excelente atención al cliente.', 'Café Central ha sido galardonado con el premio \"Mejor Café de Especialidad 2024\" otorgado por la Asociación Colombiana de Cafés Especiales, reconociendo su dedicación a promover el café colombiano de alta calidad.\n\nEl premio evalúa aspectos como la calidad del grano, métodos de preparación, conocimiento del barista y experiencia del cliente.\n\n\"Este reconocimiento es el resultado del trabajo constante de nuestro equipo y la confianza de nuestros clientes\", comentó Juan Pérez, propietario del café.\n\nCafé Central se destaca por trabajar directamente con productores locales de café, garantizando no solo la calidad del producto sino también un comercio justo.\n\nEl establecimiento, ubicado en la Avenida 19 #103-45, celebrará este logro ofreciendo degustaciones gratuitas de sus mejores preparaciones durante toda la semana.', '2024-09-29 09:15:00', 1, 2, NULL, 89, '2025-10-02 14:43:43', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `news_images`
--

CREATE TABLE `news_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `news_id` int(10) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `uploaded_by` int(10) UNSIGNED DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'admin', 'Administrador del sistema con todos los privilegios', '2025-10-02 15:03:44'),
(2, 'editor', 'Usuario que puede crear y editar contenido', '2025-10-02 15:03:44'),
(3, 'cliente', 'Usuario registrado que consume contenido', '2025-10-02 15:03:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sections`
--

CREATE TABLE `sections` (
  `id` int(10) UNSIGNED NOT NULL,
  `slug` varchar(120) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sections`
--

INSERT INTO `sections` (`id`, `slug`, `title`, `description`, `sort_order`, `created_at`) VALUES
(1, 'discotecas', 'Discotecas', 'Lugares de entretenimiento nocturno con música y baile.', 1, '2025-10-02 16:48:25'),
(2, 'licorerias', 'Licorerías', 'Establecimientos dedicados a la venta de licores y bebidas alcohólicas.', 2, '2025-10-02 16:48:25'),
(3, 'almacenes', 'Almacenes', 'Tiendas de productos variados como ropa, calzado y accesorios.', 3, '2025-10-02 16:48:25'),
(4, 'restaurantes', 'Restaurantes', 'Lugares donde se preparan y venden comidas y bebidas.', 4, '2025-10-02 16:48:25'),
(5, 'supermercados', 'Supermercados', 'Espacios comerciales donde se venden productos de consumo masivo.', 5, '2025-10-02 16:48:25'),
(6, 'farmacias', 'Farmacias', 'Establecimientos que expenden medicamentos y productos de salud.', 6, '2025-10-02 16:48:25'),
(7, 'bares', 'Bares', 'Sitios para consumo de bebidas alcohólicas y entretenimiento.', 7, '2025-10-02 16:48:25'),
(8, 'cafeterias', 'Cafeterías', 'Lugares especializados en café, repostería y comidas ligeras.', 8, '2025-10-02 16:48:25'),
(9, 'centros-comerciales', 'Centros Comerciales', 'Conjuntos de almacenes y locales en un mismo espacio.', 9, '2025-10-02 16:48:25'),
(10, 'hoteles', 'Hoteles', 'Alojamientos para turistas y visitantes.', 10, '2025-10-02 16:48:25'),
(11, 'panaderias', 'Panaderías', 'Panaderías y pastelerías que venden pan fresco, bollería y repostería.', 11, '2025-10-02 16:52:27'),
(12, 'ferreterias', 'Ferreterías', 'Tiendas que venden herramientas, materiales de construcción y suministros para bricolaje.', 12, '2025-10-03 21:35:55'),
(13, 'comidas-rapidas', 'Comidas Rápidas', 'Locales que ofrecen alimentos preparados y servicio rápido (hamburguesas, pizzas, etc.).', 13, '2025-10-03 21:35:55'),
(14, 'papelerias', 'Papelerías', 'Tiendas de suministros de oficina, papelería, útiles escolares e impresiones.', 14, '2025-10-03 21:35:55'),
(15, 'tiendas', 'Tiendas', 'Pequeños comercios de barrio que venden productos diversos.', 15, '2025-10-03 21:35:55'),
(16, 'pinateria-decoraciones', 'Piñatería y Decoraciones', 'Negocios especializados en artículos para fiestas, piñatas y decoración de eventos.', 16, '2025-10-03 21:35:55'),
(17, 'heladerias', 'Heladerías', 'Locales dedicados a la venta de helados, postres fríos y bebidas.', 17, '2025-10-03 21:35:55'),
(18, 'servicios-tecnicos', 'Servicios Técnicos', 'Talleres y servicios de reparación y mantenimiento de electrodomésticos y equipos electrónicos.', 18, '2025-10-03 21:35:55'),
(19, 'talleres-moto', 'Talleres de Moto', 'Talleres especializados en reparación y mantenimiento de motocicletas.', 19, '2025-10-03 21:35:55'),
(20, 'lavaderos', 'Lavaderos', 'Servicios de lavado y mantenimiento de vehículos.', 20, '2025-10-03 21:35:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(80) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role_id` int(10) UNSIGNED DEFAULT NULL,
  `full_name` varchar(150) DEFAULT NULL,
  `role` int(5) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `business_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del negocio al que pertenece el usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role_id`, `full_name`, `role`, `is_active`, `created_at`, `updated_at`, `business_id`) VALUES
(1, 'admin', 'admin@portal.com', '$2y$10$XQzkRVMDIdvazpQRrAda6e6xSKuA2K8IvbpjZ7sXgPiwvgjIVLMnS', NULL, 'Administrador Portal', 1, 1, '2025-10-02 14:43:42', '2025-10-02 15:10:44', NULL),
(2, 'editor1', 'editor@portal.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'Editor Principal', 2, 1, '2025-10-02 14:43:42', NULL, NULL),
(3, 'carlos_pizza', 'carlos@pizzaexpress.com', '$2y$10$VwyFdx9/ATxyaVV8LvTDze1KSneG5ug14xtYmFYPvvBaz109JxLKS', NULL, 'Carlos Rodríguez', 2, 1, '2025-10-02 14:43:42', '2025-10-02 15:53:42', 1),
(4, 'maria_burger', 'maria@burgermania.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'María González', 2, 1, '2025-10-02 14:43:42', '2025-10-02 15:51:12', 2),
(5, 'juan_cafe', 'juan@cafecentral.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'Juan Pérez', 2, 1, '2025-10-02 14:43:42', '2025-10-02 15:51:12', 7),
(6, 'ana_restaurant', 'ana@restaurantebogota.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'Ana López', 2, 1, '2025-10-02 14:43:42', '2025-10-02 15:51:12', 4);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `businesses`
--
ALTER TABLE `businesses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_section_id` (`section_id`);
ALTER TABLE `businesses` ADD FULLTEXT KEY `ft_name_desc` (`name`,`short_description`,`description`);

--
-- Indices de la tabla `business_images`
--
ALTER TABLE `business_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploaded_by` (`uploaded_by`),
  ADD KEY `idx_business_id` (`business_id`);

--
-- Indices de la tabla `business_videos`
--
ALTER TABLE `business_videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploaded_by` (`uploaded_by`),
  ADD KEY `idx_business_id` (`business_id`),
  ADD KEY `idx_display_order` (`display_order`);

--
-- Indices de la tabla `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `idx_published_at` (`published_at`);
ALTER TABLE `news` ADD FULLTEXT KEY `ft_title_content` (`title`,`summary`,`content`);

--
-- Indices de la tabla `news_images`
--
ALTER TABLE `news_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `news_id` (`news_id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indices de la tabla `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_users_roles` (`role_id`),
  ADD KEY `business_id` (`business_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `businesses`
--
ALTER TABLE `businesses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `business_images`
--
ALTER TABLE `business_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `business_videos`
--
ALTER TABLE `business_videos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `news`
--
ALTER TABLE `news`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `news_images`
--
ALTER TABLE `news_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `audit_log`
--
ALTER TABLE `audit_log`
  ADD CONSTRAINT `audit_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `businesses`
--
ALTER TABLE `businesses`
  ADD CONSTRAINT `businesses_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `businesses_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `business_images`
--
ALTER TABLE `business_images`
  ADD CONSTRAINT `business_images_ibfk_1` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `business_images_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `business_videos`
--
ALTER TABLE `business_videos`
  ADD CONSTRAINT `business_videos_ibfk_1` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `business_videos_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `news_images`
--
ALTER TABLE `news_images`
  ADD CONSTRAINT `news_images_ibfk_1` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `news_images_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_roles` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
